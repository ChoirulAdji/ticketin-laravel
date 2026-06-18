<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Mail\OrderConfirmationMail;
use App\Mail\NewOrderNotifEoMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    // Halaman pilih tiket
    public function pilihTiket(Event $event): View
    {
        if ($event->status !== 'published') abort(404);

        $categories = $event->ticketCategories;
        return view('checkout.pilih-tiket', compact('event', 'categories'));
    }

    // Simpan pilihan tiket ke session, redirect ke checkout
    public function simpanKeranjang(Request $request, Event $event): RedirectResponse
    {
        $tickets = $request->input('tickets', []);

        // Filter yang qty > 0
        $cart = array_filter($tickets, fn($qty) => intval($qty) > 0);

        if (empty($cart)) {
            return back()->withErrors(['tickets' => 'Pilih minimal 1 tiket.']);
        }

        session(["cart.{$event->id}" => $cart]);

        return redirect()->route('checkout.show', $event);
    }

    // Halaman checkout
    public function show(Event $event): RedirectResponse|View
    {
        $cart = session("cart.{$event->id}", []);

        if (empty($cart)) {
            return redirect()->route('events.pilih-tiket', $event);
        }

        $summary = [];
        $subtotal = 0;
        $totalQty = 0;

        foreach ($cart as $categoryId => $qty) {
            $qty = intval($qty);
            $category = TicketCategory::find($categoryId);
            if ($category && $qty > 0) {
                $lineTotal = $category->harga * $qty;
                $summary[] = [
                    'category'   => $category,
                    'qty'        => $qty,
                    'line_total' => $lineTotal,
                ];
                $subtotal  += $lineTotal;
                $totalQty  += $qty;
            }
        }

        $biayaLayanan = $subtotal * 0.05; // 5% biaya layanan
        $total        = $subtotal + $biayaLayanan;

        return view('checkout.index', compact('event', 'summary', 'subtotal', 'biayaLayanan', 'total', 'totalQty'));
    }

    // Proses order
    public function proses(Request $request, Event $event): RedirectResponse
    {
        // Normalize ke lowercase sebelum validasi
        $request->merge(['metode_bayar' => strtolower($request->metode_bayar)]);

        $request->validate([
            'metode_bayar' => ['required', 'string', 'in:bca,bni,bri,mandiri,gopay,ovo,dana,qris,shopepay'],
        ]);

        $cart = session("cart.{$event->id}", []);

        if (empty($cart)) {
            return redirect()->route('events.index');
        }

        DB::transaction(function () use ($request, $event, $cart) {
            $subtotal     = 0;
            $totalQty     = 0;
            $ticketItems  = [];
            $summaryParts = [];

            foreach ($cart as $categoryId => $qty) {
                $qty      = intval($qty);
                $category = TicketCategory::find($categoryId);
                if ($category && $qty > 0) {
                    $subtotal    += $category->harga * $qty;
                    $totalQty    += $qty;
                    $ticketItems[] = ['category' => $category, 'qty' => $qty];
                    $summaryParts[] = $qty . 'x ' . $category->nama_kategori;
                }
            }

            $biayaLayanan  = round($subtotal * 0.05);
            $total         = $subtotal + $biayaLayanan;
            $pendapatanEo  = $subtotal; // EO dapat subtotal (harga tiket)

            $order = Order::create([
                'order_code'     => Order::generateCode(),
                'user_id'        => Auth::id(),
                'event_id'       => $event->id,
                'total_harga'    => $total,
                'subtotal'       => $subtotal,
                'biaya_layanan'  => $biayaLayanan,
                'pendapatan_eo'  => $pendapatanEo,
                'total_qty'      => $totalQty,
                'status'         => 'pending',
                'metode_bayar'   => $request->metode_bayar,
                'ticket_summary' => implode(', ', $summaryParts),
            ]);

            foreach ($ticketItems as $item) {
                OrderItem::create([
                    'order_id'           => $order->id,
                    'ticket_category_id' => $item['category']->id,
                    'qty'                => $item['qty'],
                    'harga_satuan'       => $item['category']->harga,
                ]);
            }

            session()->forget("cart.{$event->id}");
            session(['last_order_id' => $order->id]);
        });

        $orderId = session('last_order_id');
        if ($orderId) {
            $this->sendOrderEmailsAfterResponse((int) $orderId);
        }

        return redirect()->route('checkout.sukses');
    }

    private function sendOrderEmailsAfterResponse(int $orderId): void
    {
        app()->terminating(function () use ($orderId) {
            if (! $this->mailIsConfigured()) {
                \Log::info('Email order dilewati karena konfigurasi mail belum siap.');
                return;
            }

            $createdOrder = Order::with(['user', 'event.pengelola', 'items.ticketCategory'])->find($orderId);
            if (! $createdOrder) {
                return;
            }

            try {
                Mail::to($createdOrder->user->email)
                    ->send(new OrderConfirmationMail($createdOrder));
            } catch (\Throwable $e) {
                \Log::warning('Gagal kirim email konfirmasi ke pembeli: ' . $e->getMessage());
            }

            $eoEmail = $createdOrder->event->pengelola?->email;
            if ($eoEmail) {
                try {
                    Mail::to($eoEmail)
                        ->send(new NewOrderNotifEoMail($createdOrder));
                } catch (\Throwable $e) {
                    \Log::warning('Gagal kirim email notif ke EO: ' . $e->getMessage());
                }
            }
        });
    }

    private function mailIsConfigured(): bool
    {
        $mailer = config('mail.default');
        $host = config('mail.mailers.smtp.host');
        $username = config('mail.mailers.smtp.username');
        $password = config('mail.mailers.smtp.password');

        if (in_array($mailer, ['log', 'array'], true)) {
            return true;
        }

        if ($mailer !== 'smtp') {
            return true;
        }

        return filled($host)
            && $username !== 'emailkamu@gmail.com'
            && $password !== 'app_password_gmail';
    }

    // Halaman sukses
    public function sukses(): View|RedirectResponse
    {
        $orderId = session('last_order_id');
        if (! $orderId) return redirect()->route('dashboard');

        $order = Order::with(['event', 'items.ticketCategory'])->findOrFail($orderId);

        return view('checkout.sukses', compact('order'));
    }
}
