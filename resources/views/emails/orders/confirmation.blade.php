<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pesanan Berhasil</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { background:#f3f4f6; font-family:'Segoe UI',Arial,sans-serif; color:#1f2937; }
    .wrapper { max-width:600px; margin:32px auto; }
    .card { background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.08); }
    .header { background:linear-gradient(135deg,#102A71 0%,#001840 100%); padding:32px 40px; text-align:center; }
    .header img { height:36px; margin-bottom:16px; }
    .logo-text { color:#F5C400; font-size:24px; font-weight:800; letter-spacing:-0.5px; }
    .header h1 { color:#fff; font-size:20px; font-weight:700; margin-top:8px; }
    .header p { color:rgba(255,255,255,.6); font-size:13px; margin-top:4px; }
    .body { padding:32px 40px; }
    .greeting { font-size:16px; color:#374151; margin-bottom:20px; }
    .order-box { background:#f8faff; border:1.5px solid #e0e7ff; border-radius:12px; padding:20px 24px; margin-bottom:24px; }
    .order-box .label { font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; margin-bottom:4px; }
    .order-box .value { font-size:15px; font-weight:700; color:#111827; }
    .order-code { background:#F5C400; color:#001840; font-size:22px; font-weight:800; text-align:center; letter-spacing:2px; padding:12px 20px; border-radius:10px; margin:16px 0; font-family:monospace; }
    .divider { border:none; border-top:1.5px dashed #e5e7eb; margin:20px 0; }
    .section-title { font-size:13px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; margin-bottom:12px; }
    .item-row { display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:1px solid #f3f4f6; font-size:14px; }
    .item-row:last-child { border-bottom:none; }
    .total-row { display:flex; justify-content:space-between; align-items:center; padding:12px 0; font-size:15px; font-weight:700; color:#102A71; }
    .event-cover { width:100%; height:160px; object-fit:cover; border-radius:10px; margin-bottom:16px; }
    .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:20px; }
    .info-item { background:#f9fafb; border-radius:8px; padding:12px 14px; }
    .info-item .icon { font-size:18px; margin-bottom:4px; }
    .info-item .il { font-size:11px; color:#9ca3af; font-weight:600; text-transform:uppercase; letter-spacing:.04em; }
    .info-item .iv { font-size:13px; color:#111827; font-weight:600; margin-top:2px; }
    .cta { display:block; background:#F5C400; color:#001840; text-align:center; font-weight:800; font-size:15px; padding:14px 24px; border-radius:10px; text-decoration:none; margin:24px 0; }
    .footer { background:#f9fafb; padding:24px 40px; text-align:center; }
    .footer p { font-size:12px; color:#9ca3af; line-height:1.6; }
    .footer a { color:#102A71; text-decoration:none; font-weight:600; }
    .badge { display:inline-block; background:#dcfce7; color:#16a34a; border:1px solid #bbf7d0; border-radius:20px; font-size:12px; font-weight:700; padding:4px 12px; margin-bottom:16px; }
    @media (max-width:480px) {
      .body, .header, .footer { padding:24px 20px; }
      .info-grid { grid-template-columns:1fr; }
    }
  </style>
</head>
<body>
<div class="wrapper">
  <div class="card">
    <div class="header">
      <div class="logo-text">🎟️ TicketIn</div>
      <h1>Pesanan Berhasil Dibuat!</h1>
      <p>Terima kasih telah memesan tiket di TicketIn</p>
    </div>
    <div class="body">
      <p class="greeting">Halo, <strong>{{ $order->user->nama_panggilan }}</strong>! 👋</p>
      <p style="font-size:14px;color:#6b7280;margin-bottom:20px;">
        Pesananmu sudah berhasil kami terima. Selesaikan pembayaran sebelum batas waktu agar tiket kamu dikonfirmasi.
      </p>

      {{-- Kode Order --}}
      <div class="order-box">
        <div class="label">Kode Pesanan</div>
        <div class="order-code">{{ $order->order_code }}</div>
        <div style="display:flex;justify-content:space-between;gap:16px;margin-top:8px;">
          <div>
            <div class="label">Status</div>
            <div class="value" style="color:#d97706;">⏳ Menunggu Pembayaran</div>
          </div>
          <div>
            <div class="label">Metode Bayar</div>
            <div class="value">{{ strtoupper($order->metode_bayar) }}</div>
          </div>
        </div>
      </div>

      {{-- Info Event --}}
      <img src="{{ $order->event->cover_url }}" alt="{{ $order->event->judul }}" class="event-cover"
           onerror="this.style.display='none'">

      <div class="section-title">Detail Event</div>
      <div class="info-grid">
        <div class="info-item">
          <div class="icon">📅</div>
          <div class="il">Tanggal</div>
          <div class="iv">{{ $order->event->tanggal_waktu->translatedFormat('d F Y') }}</div>
        </div>
        <div class="info-item">
          <div class="icon">🕐</div>
          <div class="il">Jam</div>
          <div class="iv">{{ $order->event->tanggal_waktu->format('H:i') }} WIB</div>
        </div>
        <div class="info-item">
          <div class="icon">📍</div>
          <div class="il">Lokasi</div>
          <div class="iv">{{ $order->event->lokasi_kota }}</div>
        </div>
        <div class="info-item">
          <div class="icon">🏟️</div>
          <div class="il">Venue</div>
          <div class="iv">{{ $order->event->venue }}</div>
        </div>
      </div>

      <p style="font-size:15px;font-weight:700;color:#111827;margin-bottom:12px;">{{ $order->event->judul }}</p>

      <hr class="divider">

      {{-- Rincian Tiket --}}
      <div class="section-title">Rincian Tiket</div>
      @foreach($order->items as $item)
      <div class="item-row">
        <span>{{ $item->ticketCategory->nama_kategori }} × {{ $item->qty }}</span>
        <span style="font-weight:600;">Rp {{ number_format($item->harga_satuan * $item->qty, 0, ',', '.') }}</span>
      </div>
      @endforeach
      @php
        $subtotal = $order->items->sum(fn($i) => $i->harga_satuan * $i->qty);
        $layanan  = $order->total_harga - $subtotal;
      @endphp
      <div class="item-row" style="color:#6b7280;">
        <span>Biaya Layanan (5%)</span>
        <span>Rp {{ number_format($layanan, 0, ',', '.') }}</span>
      </div>
      <div class="total-row">
        <span>Total Pembayaran</span>
        <span style="font-size:18px;">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
      </div>

      <a href="{{ url('/profile') }}" class="cta">Lihat Detail Pesanan →</a>

      <p style="font-size:12px;color:#9ca3af;text-align:center;">
        Simpan kode pesanan kamu. E-Ticket akan tersedia setelah pembayaran dikonfirmasi.
      </p>
    </div>
    <div class="footer">
      <p>Email ini dikirim otomatis oleh <a href="{{ config('app.url') }}">TicketIn</a>.<br>
      Pertanyaan? Hubungi kami di <a href="mailto:cs@ticketin.com">cs@ticketin.com</a></p>
    </div>
  </div>
</div>
</body>
</html>
