<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tiket - {{ $order->order_code }}</title>
    <style>
        @font-face {
            font-family: "Poppins";
            font-style: normal;
            font-weight: 400;
            src: url("{{ $fontPaths['regular'] }}") format("truetype");
        }

        @font-face {
            font-family: "Poppins";
            font-style: normal;
            font-weight: 600;
            src: url("{{ $fontPaths['semibold'] }}") format("truetype");
        }

        @font-face {
            font-family: "Poppins";
            font-style: normal;
            font-weight: 700;
            src: url("{{ $fontPaths['bold'] }}") format("truetype");
        }

        @font-face {
            font-family: "Poppins";
            font-style: normal;
            font-weight: 800;
            src: url("{{ $fontPaths['bold'] }}") format("truetype");
        }

        @font-face {
            font-family: "Poppins";
            font-style: normal;
            font-weight: 900;
            src: url("{{ $fontPaths['bold'] }}") format("truetype");
        }

        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Poppins, DejaVu Sans, Arial, sans-serif;
            background: #ffffff;
            color: #0f172a;
            font-size: 12px;
        }

        .ticket {
            width: 100%;
            border: 1px solid #dbe3ef;
            border-radius: 16px;
            overflow: hidden;
            background: #ffffff;
        }

        .header {
            background: #102A71;
            color: #ffffff;
            padding: 18px 22px;
        }

        .brand-table {
            width: 100%;
            border-collapse: collapse;
        }

        .brand-left {
            width: 74%;
            vertical-align: top;
        }

        .brand-icon {
            display: inline-block;
            width: 36px;
            height: 36px;
            margin-right: 10px;
            vertical-align: middle;
        }

        .brand-icon img {
            width: 36px;
            height: 36px;
            display: block;
        }

        .brand-word {
            display: inline-block;
            vertical-align: middle;
            color: #ffffff;
            font-size: 22px;
            line-height: 36px;
            font-weight: 700;
        }

        .status-cell {
            text-align: right;
            vertical-align: top;
        }

        .status {
            display: inline-block;
            padding: 7px 14px;
            border-radius: 18px;
            background: #22c55e;
            color: #ffffff;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: .04em;
        }

        .event-title {
            margin-top: 18px;
            font-size: 25px;
            line-height: 1.2;
            font-weight: 900;
        }

        .order-code {
            margin-top: 8px;
            color: #f5c400;
            font-family: "Courier New", DejaVu Sans Mono, Courier, monospace;
            font-size: 13px;
            font-weight: 900;
            letter-spacing: 2px;
        }

        .body {
            padding: 17px 22px 18px;
        }

        .info-table,
        .ticket-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .info-cell {
            width: 16.66%;
            padding: 0 6px 10px 0;
            vertical-align: top;
        }

        .info-box {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: #f8fafc;
            padding: 9px 10px;
            min-height: 48px;
        }

        .label {
            margin-bottom: 4px;
            color: #64748b;
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .value {
            color: #0f172a;
            font-size: 12px;
            line-height: 1.35;
            font-weight: 900;
        }

        .summary {
            margin: 2px 0 12px;
        }

        .chip {
            display: inline-block;
            margin: 0 6px 6px 0;
            padding: 6px 11px;
            border-radius: 16px;
            background: #fff7cc;
            border: 1px solid #f5c400;
            color: #7c5d00;
            font-size: 10px;
            font-weight: 900;
        }

        .divider {
            margin: 0 0 13px;
            border-top: 1.5px dashed #cbd5e1;
        }

        .content-row {
            width: 100%;
            clear: both;
            page-break-inside: avoid;
        }

        .buyer-panel {
            float: left;
            width: 25%;
            margin-right: 2%;
        }

        .tickets-panel {
            float: left;
            width: 73%;
        }

        .clear {
            clear: both;
            height: 0;
            line-height: 0;
        }

        .buyer-card,
        .note {
            border-radius: 12px;
            padding: 10px 11px;
        }

        .buyer-card {
            border: 1px solid #e5e7eb;
            background: #f8fafc;
        }

        .buyer-row {
            margin-bottom: 8px;
        }

        .buyer-row:last-child {
            margin-bottom: 0;
        }

        .note {
            margin-top: 10px;
            border: 1px solid #fde68a;
            background: #fffbeb;
            color: #92400e;
            font-size: 9px;
            line-height: 1.45;
        }

        .ticket-cell {
            width: 25%;
            padding: 0 8px 8px 0;
            vertical-align: top;
        }

        .ticket-card {
            border: 1px solid #dbe3ef;
            border-radius: 12px;
            background: #ffffff;
            padding: 8px 8px;
            text-align: center;
            min-height: 130px;
        }

        .qr {
            width: 70px;
            height: 70px;
            margin: 0 auto 6px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background: #ffffff;
            padding: 3px;
        }

        .qr img {
            width: 64px;
            height: 64px;
            display: block;
        }

        .ticket-code {
            color: #102a71;
            font-family: "Courier New", DejaVu Sans Mono, Courier, monospace;
            font-size: 9px;
            line-height: 1.25;
            font-weight: 900;
            word-break: break-all;
        }

        .ticket-category,
        .ticket-passenger {
            margin-top: 5px;
            color: #64748b;
            font-size: 9px;
            line-height: 1.25;
            font-weight: 700;
        }

        .ticket-passenger {
            color: #334155;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <table class="brand-table">
                <tr>
                    <td class="brand-left">
                        <span class="brand-icon">
                            @if($logoMarkDataUri)
                                <img src="{{ $logoMarkDataUri }}" width="36" height="36" alt="TicketIn">
                            @endif
                        </span>
                        <span class="brand-word">TicketIn</span>
                    </td>
                    <td class="status-cell">
                        <span class="status">VALID</span>
                    </td>
                </tr>
            </table>

            <div class="event-title">{{ $order->event->judul }}</div>
            <div class="order-code">{{ $order->order_code }}</div>
        </div>

        <div class="body">
            <table class="info-table">
                <tr>
                    <td class="info-cell">
                        <div class="info-box">
                            <div class="label">Pemesan</div>
                            <div class="value">{{ $order->user->nama_lengkap }}</div>
                        </div>
                    </td>
                    <td class="info-cell">
                        <div class="info-box">
                            <div class="label">Total Bayar</div>
                            <div class="value">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
                        </div>
                    </td>
                    <td class="info-cell">
                        <div class="info-box">
                            <div class="label">Tanggal</div>
                            <div class="value">{{ $order->event->tanggal_waktu->format('d M Y') }}</div>
                        </div>
                    </td>
                    <td class="info-cell">
                        <div class="info-box">
                            <div class="label">Waktu</div>
                            <div class="value">{{ $order->event->tanggal_waktu->format('H:i') }} WIB</div>
                        </div>
                    </td>
                    <td class="info-cell">
                        <div class="info-box">
                            <div class="label">Lokasi</div>
                            <div class="value">{{ $order->event->lokasi_kota }}</div>
                        </div>
                    </td>
                    <td class="info-cell">
                        <div class="info-box">
                            <div class="label">Jumlah Tiket</div>
                            <div class="value">{{ $order->total_qty }} Tiket</div>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="summary">
                @foreach($order->items as $item)
                    <span class="chip">{{ $item->qty }}x {{ $item->ticketCategory->nama_kategori ?? 'Tiket' }}</span>
                @endforeach
            </div>

            <div class="divider"></div>

            <div class="content-row">
                <div class="buyer-panel">
                    <div class="buyer-card">
                        <div class="buyer-row">
                            <div class="label">Venue</div>
                            <div class="value">{{ $order->event->venue }}</div>
                        </div>
                        <div class="buyer-row">
                            <div class="label">Kode Order</div>
                            <div class="value">{{ $order->order_code }}</div>
                        </div>
                        <div class="buyer-row">
                            <div class="label">Status</div>
                            <div class="value">VALID</div>
                        </div>
                    </div>
                    <div class="note">
                        Tunjukkan QR Code pada tiket ini kepada petugas di pintu masuk event.
                    </div>
                </div>

                <div class="tickets-panel">
                    <table class="ticket-grid">
                        @foreach(collect($tickets)->chunk(4) as $row)
                            <tr>
                                @foreach($row as $ticket)
                                    <td class="ticket-cell">
                                        <div class="ticket-card">
                                            <div class="qr">
                                                <img src="{{ $ticket['qr_data_uri'] }}" alt="QR {{ $ticket['code'] }}">
                                            </div>
                                            <div class="ticket-code">{{ $ticket['code'] }}</div>
                                            <div class="ticket-category">{{ $ticket['category'] }}</div>
                                            @if($ticket['passenger_name'])
                                                <div class="ticket-passenger">{{ $ticket['passenger_name'] }}</div>
                                            @endif
                                        </div>
                                    </td>
                                @endforeach
                                @for($i = $row->count(); $i < 4; $i++)
                                    <td class="ticket-cell">&nbsp;</td>
                                @endfor
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</body>
</html>
