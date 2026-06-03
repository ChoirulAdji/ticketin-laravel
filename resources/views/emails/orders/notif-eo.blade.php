<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pembeli Baru</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { background:#f3f4f6; font-family:'Segoe UI',Arial,sans-serif; color:#1f2937; }
    .wrapper { max-width:600px; margin:32px auto; }
    .card { background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.08); }
    .header { background:linear-gradient(135deg,#102A71 0%,#001840 100%); padding:28px 40px; text-align:center; }
    .logo-text { color:#F5C400; font-size:22px; font-weight:800; }
    .header h1 { color:#fff; font-size:19px; font-weight:700; margin-top:8px; }
    .header p { color:rgba(255,255,255,.6); font-size:13px; margin-top:4px; }
    .body { padding:32px 40px; }
    .alert-box { background:#fffbeb; border:1.5px solid #fde68a; border-radius:12px; padding:16px 20px; margin-bottom:24px; display:flex; align-items:flex-start; gap:12px; }
    .alert-box .icon { font-size:28px; flex-shrink:0; }
    .alert-box h2 { font-size:15px; font-weight:700; color:#92400e; margin-bottom:4px; }
    .alert-box p { font-size:13px; color:#b45309; }
    .stat-grid { display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; margin-bottom:24px; }
    .stat { background:#f8faff; border:1.5px solid #e0e7ff; border-radius:10px; padding:14px; text-align:center; }
    .stat .num { font-size:22px; font-weight:800; color:#102A71; }
    .stat .lbl { font-size:11px; color:#9ca3af; font-weight:600; text-transform:uppercase; letter-spacing:.04em; margin-top:2px; }
    .section-title { font-size:12px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; margin-bottom:10px; }
    .detail-row { display:flex; justify-content:space-between; padding:9px 0; border-bottom:1px solid #f3f4f6; font-size:14px; }
    .detail-row:last-child { border-bottom:none; }
    .detail-row .lbl { color:#6b7280; }
    .detail-row .val { font-weight:600; color:#111827; }
    .cta { display:block; background:#102A71; color:#F5C400; text-align:center; font-weight:800; font-size:14px; padding:13px 24px; border-radius:10px; text-decoration:none; margin:24px 0; }
    .footer { background:#f9fafb; padding:20px 40px; text-align:center; }
    .footer p { font-size:12px; color:#9ca3af; }
    .footer a { color:#102A71; text-decoration:none; font-weight:600; }
    @media (max-width:480px) {
      .body, .header, .footer { padding:20px 16px; }
      .stat-grid { grid-template-columns:1fr 1fr; }
    }
  </style>
</head>
<body>
<div class="wrapper">
  <div class="card">
    <div class="header">
      <div class="logo-text">🎟️ TicketIn</div>
      <h1>🎉 Ada Pembeli Baru!</h1>
      <p>Notifikasi untuk pengelola event</p>
    </div>
    <div class="body">
      <div class="alert-box">
        <div class="icon">🔔</div>
        <div>
          <h2>Pesanan baru masuk untuk eventmu!</h2>
          <p>{{ $order->user->nama_lengkap }} baru saja memesan tiket <strong>{{ $order->event->judul }}</strong>.</p>
        </div>
      </div>

      {{-- Stats --}}
      <div class="stat-grid">
        <div class="stat">
          <div class="num">{{ $order->total_qty }}</div>
          <div class="lbl">Tiket</div>
        </div>
        <div class="stat">
          <div class="num" style="font-size:14px;">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
          <div class="lbl">Total</div>
        </div>
        <div class="stat">
          <div class="num" style="color:#d97706;">⏳</div>
          <div class="lbl">{{ ucfirst($order->status) }}</div>
        </div>
      </div>

      {{-- Detail Pembeli --}}
      <div class="section-title">Detail Pembeli</div>
      <div style="background:#f9fafb;border-radius:10px;padding:16px 20px;margin-bottom:20px;">
        <div class="detail-row">
          <span class="lbl">Nama</span>
          <span class="val">{{ $order->user->nama_lengkap }}</span>
        </div>
        <div class="detail-row">
          <span class="lbl">Email</span>
          <span class="val">{{ $order->user->email }}</span>
        </div>
        <div class="detail-row">
          <span class="lbl">No. HP</span>
          <span class="val">{{ $order->user->no_hp ? '+62'.$order->user->no_hp : '-' }}</span>
        </div>
        <div class="detail-row">
          <span class="lbl">Kode Order</span>
          <span class="val" style="font-family:monospace;color:#102A71;">{{ $order->order_code }}</span>
        </div>
        <div class="detail-row">
          <span class="lbl">Metode Bayar</span>
          <span class="val">{{ strtoupper($order->metode_bayar) }}</span>
        </div>
      </div>

      {{-- Rincian Tiket --}}
      <div class="section-title">Tiket yang Dipesan</div>
      <div style="background:#f9fafb;border-radius:10px;padding:16px 20px;margin-bottom:20px;">
        @foreach($order->items as $item)
        <div class="detail-row">
          <span class="lbl">{{ $item->ticketCategory->nama_kategori }}</span>
          <span class="val">{{ $item->qty }}x — Rp {{ number_format($item->harga_satuan * $item->qty, 0, ',', '.') }}</span>
        </div>
        @endforeach
      </div>

      <a href="{{ url('/pengelola') }}" class="cta">Buka Dashboard EO →</a>

      <p style="font-size:12px;color:#9ca3af;text-align:center;">
        Pembayaran masih berstatus <strong>pending</strong>. Tiket akan aktif setelah pembayaran dikonfirmasi.
      </p>
    </div>
    <div class="footer">
      <p>Email ini dikirim otomatis oleh <a href="{{ config('app.url') }}">TicketIn</a>.</p>
    </div>
  </div>
</div>
</body>
</html>
