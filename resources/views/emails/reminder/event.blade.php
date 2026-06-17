<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reminder Event Besok</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { background:#f3f4f6; font-family:'Segoe UI',Arial,sans-serif; color:#1f2937; }
    .wrapper { max-width:600px; margin:32px auto; }
    .card { background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.08); }
    .header { background:linear-gradient(135deg,#F5C400 0%,#e6a800 100%); padding:32px 40px; text-align:center; }
    .logo-text { color:#001840; font-size:22px; font-weight:800; }
    .header h1 { color:#001840; font-size:22px; font-weight:800; margin-top:10px; }
    .header p { color:rgba(0,24,64,.6); font-size:13px; margin-top:4px; }
    .countdown { background:#001840; color:#F5C400; font-size:32px; font-weight:900; text-align:center; padding:16px; letter-spacing:2px; }
    .countdown small { display:block; font-size:13px; color:rgba(255,255,255,.6); font-weight:400; letter-spacing:0; margin-top:4px; }
    .body { padding:32px 40px; }
    .event-cover { width:100%; height:160px; object-fit:cover; border-radius:12px; margin-bottom:20px; }
    .event-title { font-size:18px; font-weight:800; color:#111827; margin-bottom:16px; }
    .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:24px; }
    .info-item { background:#fffbeb; border:1.5px solid #fde68a; border-radius:10px; padding:12px 14px; }
    .info-item .icon { font-size:18px; margin-bottom:4px; }
    .info-item .il { font-size:11px; color:#92400e; font-weight:700; text-transform:uppercase; letter-spacing:.04em; }
    .info-item .iv { font-size:13px; color:#111827; font-weight:600; margin-top:2px; }
    .ticket-box { background:linear-gradient(135deg,#102A71,#001840); border-radius:12px; padding:20px; color:white; margin-bottom:24px; position:relative; overflow:hidden; }
    .ticket-box::before { content:''; position:absolute; right:-10px; top:50%; transform:translateY(-50%); font-size:80px; opacity:.1; }
    .ticket-box .tk-label { font-size:11px; color:rgba(255,255,255,.5); text-transform:uppercase; letter-spacing:.05em; }
    .ticket-box .tk-code { font-size:20px; font-weight:800; color:#F5C400; font-family:monospace; letter-spacing:2px; margin-top:4px; }
    .ticket-box .tk-summary { font-size:13px; color:rgba(255,255,255,.7); margin-top:8px; }
    .checklist { margin-bottom:24px; }
    .checklist h3 { font-size:13px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; margin-bottom:10px; }
    .check-item { display:flex; align-items:center; gap:10px; padding:8px 0; font-size:14px; color:#374151; border-bottom:1px solid #f3f4f6; }
    .check-item:last-child { border-bottom:none; }
    .check-icon { width:22px; height:22px; background:#dcfce7; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:12px; }
    .cta { display:block; background:#F5C400; color:#001840; text-align:center; font-weight:800; font-size:15px; padding:14px 24px; border-radius:10px; text-decoration:none; margin-bottom:20px; }
    .footer { background:#f9fafb; padding:20px 40px; text-align:center; }
    .footer p { font-size:12px; color:#9ca3af; }
    .footer a { color:#102A71; text-decoration:none; font-weight:600; }
    @media (max-width:480px) {
      .body, .header, .footer { padding:20px 16px; }
      .info-grid { grid-template-columns:1fr; }
    }
  </style>
</head>
<body>
<div class="wrapper">
  <div class="card">
    <div class="header">
      <div class="logo-text"> TicketIn</div>
      <h1>⏰ Besok Eventnya!</h1>
      <p>Jangan sampai ketinggalan, sudah siap?</p>
    </div>

    <div class="countdown">
      BESOK!
      <small>{{ $order->event->tanggal_waktu->format('H:i') }} WIB — {{ $order->event->tanggal_waktu->translatedFormat('d F Y') }}</small>
    </div>

    <div class="body">
      <p style="font-size:15px;color:#374151;margin-bottom:20px;">
        Halo <strong>{{ $order->user->nama_panggilan }}</strong>! <br>
        Ini pengingat bahwa event yang kamu tunggu-tunggu <strong>tinggal 1 hari lagi</strong>!
      </p>

      <img src="{{ $order->event->cover_url }}" alt="{{ $order->event->judul }}" class="event-cover"
           onerror="this.style.display='none'">

      <p class="event-title">{{ $order->event->judul }}</p>

      <div class="info-grid">
        <div class="info-item">
          <div class="icon"></div>
          <div class="il">Tanggal</div>
          <div class="iv">{{ $order->event->tanggal_waktu->translatedFormat('d F Y') }}</div>
        </div>
        <div class="info-item">
          <div class="icon"></div>
          <div class="il">Jam Mulai</div>
          <div class="iv">{{ $order->event->tanggal_waktu->format('H:i') }} WIB</div>
        </div>
        <div class="info-item">
          <div class="icon"></div>
          <div class="il">Kota</div>
          <div class="iv">{{ $order->event->lokasi_kota }}</div>
        </div>
        <div class="info-item">
          <div class="icon">️</div>
          <div class="il">Venue</div>
          <div class="iv">{{ $order->event->venue }}</div>
        </div>
      </div>

      {{-- Kode Tiket --}}
      <div class="ticket-box">
        <div class="tk-label">Kode Tiketmu</div>
        <div class="tk-code">{{ $order->order_code }}</div>
        <div class="tk-summary">{{ $order->ticket_summary }} · {{ $order->total_qty }} tiket</div>
      </div>

      {{-- Checklist persiapan --}}
      <div class="checklist">
        <h3>Checklist Persiapan</h3>
        <div class="check-item"><div class="check-icon"></div> Simpan kode tiket kamu</div>
        <div class="check-item"><div class="check-icon"></div> Cek lokasi venue di Google Maps</div>
        <div class="check-item"><div class="check-icon"></div> Datang 30 menit sebelum acara</div>
        <div class="check-item"><div class="check-icon"></div> Bawa identitas diri (KTP/SIM)</div>
        <div class="check-item"><div class="check-icon"></div> Charge HP kamu penuh</div>
      </div>

      <a href="{{ url('/profile') }}" class="cta">Lihat E-Ticket Saya →</a>

      <p style="font-size:12px;color:#9ca3af;text-align:center;">
        Selamat menikmati event! Jangan lupa kasih ulasan setelah selesai ya 
      </p>
    </div>
    <div class="footer">
      <p>Email ini dikirim otomatis oleh <a href="{{ config('app.url') }}">TicketIn</a>.<br>
      Pertanyaan? <a href="mailto:cs@ticketin.com">cs@ticketin.com</a></p>
    </div>
  </div>
</div>
</body>
</html>
