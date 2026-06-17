<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LaporanController extends Controller
{
    // ── Halaman laporan Admin ─────────────────────────────────────────
    public function adminIndex(Request $request)
    {
        [$orders, $summary] = $this->queryAdmin($request);
        $events = Event::orderBy('judul')->get(['id', 'judul']);
        return view('admin.laporan', compact('orders', 'summary', 'events'));
    }

    // ── Download Excel Admin ──────────────────────────────────────────
    public function adminExport(Request $request)
    {
        [$orders] = $this->queryAdmin($request);
        $filename = 'laporan-penjualan-admin-' . now()->format('Ymd-His') . '.csv';
        return $this->streamCsv($filename, $this->buildRows($orders, 'admin'));
    }

    // ── Halaman laporan EO ────────────────────────────────────────────
    public function eoIndex(Request $request)
    {
        [$orders, $summary] = $this->queryEo($request);
        $events = Event::where('pengelola_id', auth()->id())->orderBy('judul')->get(['id', 'judul']);
        return view('pengelola.laporan', compact('orders', 'summary', 'events'));
    }

    // ── Download Excel EO ─────────────────────────────────────────────
    public function eoExport(Request $request)
    {
        [$orders] = $this->queryEo($request);
        $filename = 'laporan-penjualan-eo-' . now()->format('Ymd-His') . '.csv';
        return $this->streamCsv($filename, $this->buildRows($orders, 'eo'));
    }

    // ── Query Admin ───────────────────────────────────────────────────
    private function queryAdmin(Request $request): array
    {
        $query = Order::with(['user', 'event.pengelola', 'items.ticketCategory'])
            ->when($request->filled('event_id'),   fn($q) => $q->where('event_id', $request->event_id))
            ->when($request->filled('status'),     fn($q) => $q->where('status', $request->status))
            ->when($request->filled('metode'),     fn($q) => $q->where('metode_bayar', $request->metode))
            ->when($request->filled('dari'),       fn($q) => $q->whereDate('created_at', '>=', $request->dari))
            ->when($request->filled('sampai'),     fn($q) => $q->whereDate('created_at', '<=', $request->sampai))
            ->latest();

        $orders  = $query->paginate(25)->withQueryString();
        $allData = $query->get();

        $paid = $allData->where('status','paid');
        $summary = [
            'total_pesanan'    => $allData->count(),
            'total_pendapatan' => $paid->sum('total_harga'),
            'total_biaya_platform' => $paid->sum('biaya_layanan'),
            'total_pendapatan_eo'  => $paid->sum('pendapatan_eo'),
            'total_tiket'      => $paid->sum('total_qty'),
            'pending'          => $allData->where('status','pending')->count(),
        ];

        return [$orders, $summary];
    }

    // ── Query EO ──────────────────────────────────────────────────────
    private function queryEo(Request $request): array
    {
        $pengelolaId = auth()->id();

        $query = Order::with(['user', 'event', 'items.ticketCategory'])
            ->whereHas('event', fn($q) => $q->where('pengelola_id', $pengelolaId))
            ->when($request->filled('event_id'),   fn($q) => $q->where('event_id', $request->event_id))
            ->when($request->filled('status'),     fn($q) => $q->where('status', $request->status))
            ->when($request->filled('metode'),     fn($q) => $q->where('metode_bayar', $request->metode))
            ->when($request->filled('dari'),       fn($q) => $q->whereDate('created_at', '>=', $request->dari))
            ->when($request->filled('sampai'),     fn($q) => $q->whereDate('created_at', '<=', $request->sampai))
            ->latest();

        $orders  = $query->paginate(25)->withQueryString();
        $allData = $query->get();

        $paid = $allData->where('status','paid');
        $summary = [
            'total_pesanan'    => $allData->count(),
            'total_pendapatan' => $paid->sum('pendapatan_eo'), // EO hanya dapat subtotal
            'total_biaya_platform' => $paid->sum('biaya_layanan'), // info saja
            'total_tiket'      => $paid->sum('total_qty'),
            'pending'          => $allData->where('status','pending')->count(),
        ];

        return [$orders, $summary];
    }

    // ── Build CSV rows ────────────────────────────────────────────────
    private function buildRows($orders, string $mode): array
    {
        $headers = ['No', 'Kode Order', 'Nama Pembeli', 'Email', 'No. HP',
                    'Event', 'Tanggal Event', 'Kategori Tiket', 'Qty', 'Harga Satuan',
                    'Subtotal Tiket', 'Biaya Platform (5%)', 'Pendapatan EO', 'Total Bayar', 'Metode Bayar', 'Status', 'Tanggal Pesan'];

        if ($mode === 'admin') {
            array_splice($headers, 5, 0, ['EO / Pengelola']);
        }

        $rows = [$headers];
        $no   = 1;

        // Get all data without pagination for export
        foreach ($orders as $order) {
            $items = $order->items;

            if ($items->isEmpty()) {
                $row = $this->buildRow($order, null, $no++, $mode);
                $rows[] = $row;
            } else {
                foreach ($items as $item) {
                    $row = $this->buildRow($order, $item, $no++, $mode);
                    $rows[] = $row;
                }
            }
        }

        return $rows;
    }

    private function buildRow($order, $item, int $no, string $mode): array
    {
        $itemSubtotal = $item ? ($item->harga_satuan * $item->qty) : $order->subtotal;
        $layanan      = $order->biaya_layanan ?? ($order->total_harga - $order->items->sum(fn($i) => $i->harga_satuan * $i->qty));
        $pendapatanEo = $order->pendapatan_eo ?? ($order->total_harga - $layanan);

        $base = [
            $no,
            $order->order_code,
            $order->user->nama_lengkap ?? '-',
            $order->user->email ?? '-',
            $order->user->no_hp ? '+62'.$order->user->no_hp : '-',
        ];

        if ($mode === 'admin') {
            $base[] = $order->event->pengelola->nama_lengkap ?? '-';
        }

        return array_merge($base, [
            $order->event->judul ?? '-',
            $order->event->tanggal_waktu?->format('d/m/Y H:i') ?? '-',
            $item?->ticketCategory?->nama_kategori ?? 'Semua Tiket',
            $item?->qty ?? $order->total_qty,
            $item?->harga_satuan ?? '-',
            $order->subtotal ?? $itemSubtotal,
            $order->biaya_layanan ?? $layanan,
            $order->pendapatan_eo ?? $pendapatanEo,
            $order->total_harga,
            strtoupper($order->metode_bayar),
            ucfirst($order->status),
            $order->created_at->format('d/m/Y H:i'),
        ]);
    }

    // ── Stream CSV response ───────────────────────────────────────────
    private function streamCsv(string $filename, array $rows): Response
    {
        $content  = "\xEF\xBB\xBF"; // BOM agar Excel baca UTF-8 dengan benar
        foreach ($rows as $row) {
            $cells = array_map(function ($cell) {
                $cell = str_replace('"', '""', (string) $cell);
                return '"' . $cell . '"';
            }, $row);
            $content .= implode(',', $cells) . "\r\n";
        }

        return response($content, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ]);
    }
}
