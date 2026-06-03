@extends('layouts.app')
@section('title','Tentang Kami — TicketIn')

@push('styles')
<style>
  .card-hover { transition:all .3s ease; }
  .card-hover:hover { transform:translateY(-6px); box-shadow:0 20px 40px rgba(0,24,64,.12); }
  .fade-in { opacity:0; transform:translateY(24px); transition:opacity .6s ease,transform .6s ease; }
  .fade-in.visible { opacity:1; transform:none; }
  .team-card { transition:all .3s; }
  .team-card:hover { transform:translateY(-4px); box-shadow:0 12px 30px rgba(0,24,64,.1); }
</style>
@endpush

@section('content')

<!-- Hero -->
<section class="pt-[90px] py-14 bg-gradient-to-br from-navy-deep to-navy-mid text-white">
  <div class="max-w-7xl px-10 py-5 text-left mx-auto">
    <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Tentang TicketIn</h1>
    <p class="text-white/80 max-w-2xl">TicketIn adalah platform digital pemesanan tiket event lokal yang dirancang untuk memudahkan siapa saja dalam menemukan dan menghadiri event terbaik di sekitar mereka.</p>
  </div>
</section>

<section class="max-w-7xl mx-auto px-6 py-16 space-y-16">

  <!-- Kenapa pilih TicketIn -->
  <div class="fade-in visible">
    <h2 class="text-2xl font-extrabold text-navy-deep mb-7">Kenapa Pilih TicketIn?</h2>
    <div class="grid md:grid-cols-4 gap-6">
      @foreach([
        ['🎯','Mudah Digunakan','Antarmuka sederhana dan jelas. Pengguna bisa mencari dan memesan tiket hanya dalam beberapa langkah tanpa proses yang rumit.'],
        ['📋','Informasi Lengkap','Setiap event dilengkapi detail penting seperti jadwal, lokasi, harga, dan kategori sehingga pengguna bisa mengambil keputusan dengan cepat.'],
        ['🔒','Pembayaran Aman','Mendukung berbagai metode pembayaran seperti transfer bank, e-wallet, dan QRIS dengan sistem yang terjamin keamanannya.'],
        ['🏢','Untuk Pengelola','Pengelola event dapat mendaftarkan diri dan mengelola tiket secara mandiri melalui dashboard yang intuitif dan lengkap.'],
      ] as [$icon,$title,$desc])
      <div class="bg-white p-6 rounded-xl shadow card-hover">
        <div class="text-3xl mb-3">{{ $icon }}</div>
        <h3 class="font-bold text-navy-deep mb-2">{{ $title }}</h3>
        <p class="text-gray-500 text-sm">{{ $desc }}</p>
      </div>
      @endforeach
    </div>
  </div>

  <!-- Visi Misi -->
  <div class="fade-in visible grid md:grid-cols-2 gap-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
      <div class="text-4xl mb-4">🎯</div>
      <h2 class="text-xl font-bold text-navy-deep mb-3">Visi Kami</h2>
      <p class="text-gray-500 leading-relaxed">Menjadi platform tiket event #1 di Indonesia yang memudahkan setiap orang untuk menemukan, membeli, dan menikmati pengalaman event terbaik — kapan saja, di mana saja.</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
      <div class="text-4xl mb-4">🚀</div>
      <h2 class="text-xl font-bold text-navy-deep mb-3">Misi Kami</h2>
      <ul class="space-y-3">
        @foreach(['Menyederhanakan proses pembelian tiket dengan teknologi modern','Membantu penyelenggara event menjangkau audiens yang lebih luas','Memberikan pengalaman terbaik dari penemuan hingga pintu masuk event','Mendukung ekosistem hiburan dan industri kreatif Indonesia'] as $m)
        <li class="flex items-start gap-3 text-gray-500 text-sm">
          <span class="text-gold mt-0.5 flex-shrink-0 font-bold">✓</span>{{ $m }}
        </li>
        @endforeach
      </ul>
    </div>
  </div>

  <!-- Stats -->
  <div class="fade-in visible">
    <div class="bg-navy-deep rounded-2xl p-10">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
        @foreach([['500+','Event Digelar'],['50K+','Tiket Terjual'],['30K+','Pengguna Aktif'],['20+','Kota di Indonesia']] as [$num,$label])
        <div>
          <p class="text-3xl font-extrabold text-gold">{{ $num }}</p>
          <p class="text-white/60 text-sm mt-1">{{ $label }}</p>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- Tim -->
  <div class="fade-in visible">
    <h2 class="text-2xl font-extrabold text-navy-deep mb-7 text-center">Tim <span class="text-gold">TicketIn</span></h2>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
      @foreach([['Budi Santoso','CEO & Co-Founder','BS','Mantan engineer Gojek dengan 8 tahun pengalaman di industri teknologi.'],['Sari Dewi','COO & Co-Founder','SD','Berpengalaman mengelola ratusan event besar di Indonesia selama 10 tahun.'],['Andi Pratama','CTO','AP','Full-stack engineer spesialis platform tiket dan sistem pembayaran digital.']] as [$nama,$jabatan,$inisial,$bio])
      <div class="team-card bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
        <div class="w-16 h-16 rounded-2xl bg-navy-mid flex items-center justify-center mx-auto mb-4 text-gold font-extrabold text-xl">{{ $inisial }}</div>
        <h3 class="font-bold text-navy-deep">{{ $nama }}</h3>
        <p class="text-gold text-xs font-semibold mt-1 mb-3">{{ $jabatan }}</p>
        <p class="text-gray-500 text-sm leading-relaxed">{{ $bio }}</p>
      </div>
      @endforeach
    </div>
  </div>

  <!-- CTA -->
  <div class="fade-in visible text-center bg-white rounded-2xl shadow-sm border border-gray-100 p-10">
    <h2 class="text-2xl font-extrabold text-navy-deep mb-3">Siap Mulai?</h2>
    <p class="text-gray-500 mb-6">Bergabung dengan ribuan pengguna yang sudah menikmati kemudahan TicketIn.</p>
    <div class="flex gap-3 justify-center flex-wrap">
      <a href="{{ route('events.index') }}" class="bg-gold text-navy-deep font-bold px-6 py-3 rounded-xl hover:bg-gold-light transition-all text-sm shadow-sm">🎟️ Temukan Event</a>
      @guest
      <a href="{{ route('register') }}" class="bg-navy-mid text-white font-bold px-6 py-3 rounded-xl hover:bg-navy-deep transition-all text-sm">Daftar Gratis →</a>
      @endguest
    </div>
  </div>

</section>
@endsection

@push('scripts')
<script>
  const obs = new IntersectionObserver(entries=>entries.forEach(e=>{ if(e.isIntersecting) e.target.classList.add('visible'); }));
  document.querySelectorAll('.fade-in').forEach(el=>obs.observe(el));
</script>
@endpush
