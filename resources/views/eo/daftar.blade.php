@extends('layouts.app')
@section('title', 'Daftar Jadi Pengelola Event — TicketIn')

@push('styles')
<style>
  .form-input { border:1.5px solid #e5e7eb; border-radius:12px; outline:none; transition:all .2s; color:#1e293b; background:white; width:100%; }
  .form-input:focus { border-color:#F5C400; box-shadow:0 0 0 3px rgba(245,196,0,.15); }
  .form-input::placeholder { color:#9ca3af; }
  .form-label { display:block; font-size:.75rem; font-weight:700; color:#374151; margin-bottom:6px; }
  .step-card { background:white; border:1px solid #e5e7eb; border-radius:16px; padding:24px; margin-bottom:20px; }
</style>
@endpush

@section('content')
<div class="pt-24 max-w-4xl mx-auto px-6 py-10">

  <div class="text-center mb-10">
    <div class="w-16 h-16 bg-gold rounded-2xl flex items-center justify-center mx-auto mb-4">
      <svg class="w-9 h-9 text-navy-deep" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
    </div>
    <h1 class="text-3xl font-extrabold text-navy-deep mb-2">Daftar Jadi Pengelola Event</h1>
    <p class="text-gray-500 max-w-xl mx-auto">Bergabung sebagai EO di TicketIn dan mulai jual tiket eventmu ke ribuan pengguna.</p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Keuntungan --}}
    <div class="lg:col-span-1">
      <div class="bg-navy-deep rounded-2xl p-6 text-white sticky top-28">
        <h2 class="font-bold text-lg mb-5 text-gold">Keuntungan Jadi EO</h2>
        <div class="space-y-3">
          @foreach([
            ['','Buat Event Tak Terbatas','Buat dan publish event kapan saja'],
            ['','Kelola Tiket Sendiri','Atur kategori, harga, dan kuota tiket'],
            ['','Dashboard Lengkap','Pantau penjualan dan pendapatan real-time'],
            ['','Cairkan Dana Cepat','Dana event langsung ke rekeningmu'],
            ['','Notifikasi Realtime','Info langsung tiap ada pesanan baru'],
            ['','Laporan Detail','Export laporan penjualan per event'],
          ] as [$icon,$title,$desc])
          <div class="flex items-start gap-3 p-3 rounded-xl" style="background:rgba(255,255,255,.08)">
            <span class="text-xl flex-shrink-0">{{ $icon }}</span>
            <div>
              <p class="font-semibold text-sm">{{ $title }}</p>
              <p class="text-white/60 text-xs mt-0.5">{{ $desc }}</p>
            </div>
          </div>
          @endforeach
        </div>
        <div class="mt-5 pt-5 border-t border-white/10 text-center">
          <p class="text-white/50 text-xs">Proses verifikasi 1-3 hari kerja</p>
        </div>
      </div>
    </div>

    {{-- Form --}}
    <div class="lg:col-span-2">

      @if($errors->any())
      <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl mb-6 text-sm">
        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
      @endif

      <form method="POST" action="{{ route('eo.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Step 1: Info Organisasi --}}
        <div class="step-card">
          <h3 class="font-bold text-navy-deep mb-5 flex items-center gap-2">
            <span class="w-7 h-7 bg-navy-mid rounded-full text-white text-xs flex items-center justify-center font-bold flex-shrink-0">1</span>
            Informasi Organisasi / EO
          </h3>
          <div class="space-y-4">
            <div>
              <label class="form-label">Nama Organisasi / EO *</label>
              <input type="text" name="nama_organisasi" value="{{ old('nama_organisasi') }}"
                     placeholder="PT Kreatif Nusantara / @NamaEO"
                     class="form-input px-4 py-3 text-sm" required>
              <p class="text-gray-400 text-xs mt-1">Nama yang ditampilkan di halaman event kamu.</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="form-label">Jenis Entitas *</label>
                <select name="jenis_entitas" class="form-input px-4 py-3 text-sm" required>
                  <option value="">Pilih tipe</option>
                  @foreach(['perorangan'=>'Perorangan','cv'=>'CV / Firma','pt'=>'PT','yayasan'=>'Yayasan / NGO','komunitas'=>'Komunitas'] as $v=>$l)
                  <option value="{{ $v }}" {{ old('jenis_entitas')===$v?'selected':'' }}>{{ $l }}</option>
                  @endforeach
                </select>
              </div>
              <div>
                <label class="form-label">Skala Event *</label>
                <select name="skala_event" class="form-input px-4 py-3 text-sm" required>
                  <option value="">Pilih skala</option>
                  @foreach(['kecil'=>'Kecil (<100 orang)','menengah'=>'Menengah (100-1K)','besar'=>'Besar (>1.000)'] as $v=>$l)
                  <option value="{{ $v }}" {{ old('skala_event')===$v?'selected':'' }}>{{ $l }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div>
              <label class="form-label">Alamat Resmi Organisasi *</label>
              <input type="text" name="alamat_organisasi" value="{{ old('alamat_organisasi') }}"
                     placeholder="Jl. Basuki Rahmat No. 10, Surabaya"
                     class="form-input px-4 py-3 text-sm" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="form-label">No. HP Bisnis *</label>
                <input type="tel" name="no_hp_bisnis" value="{{ old('no_hp_bisnis') }}"
                       placeholder="081234567890" class="form-input px-4 py-3 text-sm" required>
              </div>
              <div>
                <label class="form-label">Website / Media Sosial</label>
                <input type="text" name="website" value="{{ old('website') }}"
                       placeholder="https://instagram.com/namaeo" class="form-input px-4 py-3 text-sm">
              </div>
            </div>
          </div>
        </div>

        {{-- Step 2: Dokumen --}}
        <div class="step-card">
          <h3 class="font-bold text-navy-deep mb-5 flex items-center gap-2">
            <span class="w-7 h-7 bg-navy-mid rounded-full text-white text-xs flex items-center justify-center font-bold flex-shrink-0">2</span>
            Dokumen & Verifikasi
          </h3>
          <div class="space-y-4">
            <div>
              <label class="form-label">NPWP</label>
              <input type="text" name="npwp" value="{{ old('npwp') }}"
                     placeholder="00.000.000.0-000.000" class="form-input px-4 py-3 text-sm" required>
              <p class="text-gray-400 text-xs mt-1">NPWP diperlukan untuk proses verifikasi dan pencairan dana.</p>
            </div>
            <div>
              <label class="form-label">Upload Dokumen Legalitas</label>
              <input type="file" name="dokumen_legalitas" accept=".pdf,.jpg,.jpeg,.png" required
                     class="form-input px-4 py-3 text-sm text-gray-500">
              <p class="text-gray-400 text-xs mt-1">KTP, Akta Pendirian, atau SIUP. Format PDF/JPG/PNG, maks 5MB.</p>
            </div>
          </div>
        </div>

        {{-- Step 3: Rekening --}}
        <div class="step-card">
          <h3 class="font-bold text-navy-deep mb-5 flex items-center gap-2">
            <span class="w-7 h-7 bg-navy-mid rounded-full text-white text-xs flex items-center justify-center font-bold flex-shrink-0">3</span>
            Rekening Pencairan Dana
          </h3>
          <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="form-label">Bank *</label>
                <select name="bank" class="form-input px-4 py-3 text-sm" required>
                  <option value="">Pilih bank</option>
                  @foreach(['bca'=>'BCA','bni'=>'BNI','bri'=>'BRI','mandiri'=>'Mandiri','bsi'=>'BSI','cimb'=>'CIMB Niaga','lain'=>'Bank Lainnya'] as $v=>$l)
                  <option value="{{ $v }}" {{ old('bank')===$v?'selected':'' }}>{{ $l }}</option>
                  @endforeach
                </select>
              </div>
              <div>
                <label class="form-label">Nomor Rekening *</label>
                <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening') }}"
                       placeholder="1234567890" class="form-input px-4 py-3 text-sm" required>
              </div>
            </div>
            <div>
              <label class="form-label">Nama Pemilik Rekening *</label>
              <input type="text" name="nama_rekening" value="{{ old('nama_rekening') }}"
                     placeholder="Nama sesuai buku tabungan / KTP" class="form-input px-4 py-3 text-sm" required>
              <p class="text-gray-400 text-xs mt-1">Pastikan nama sesuai identitas terdaftar di bank.</p>
            </div>
          </div>
        </div>

        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6 text-sm text-blue-700 flex gap-3 items-start">
          <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <p>Dengan mengajukan pendaftaran ini, kamu menyetujui <strong>Syarat & Ketentuan</strong> pengelola event TicketIn. Pengajuan akan direview oleh tim admin dalam <strong>1-3 hari kerja</strong>.</p>
        </div>

        <button type="submit" class="w-full bg-gold text-navy-deep font-bold py-4 rounded-xl hover:bg-gold-light transition-all text-sm">
           Kirim Pengajuan Jadi EO
        </button>
      </form>
    </div>
  </div>
</div>
@endsection
