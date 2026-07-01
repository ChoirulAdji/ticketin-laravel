@extends('layouts.app')
@section('title', 'Edit Profil — TicketIn')

@push('styles')
<style>
  .form-input { border:1.5px solid #e5e7eb; border-radius:12px; outline:none; transition:all .2s; color:#1e293b; background:white; width:100%; }
  .form-input:focus { border-color:#F5C400; box-shadow:0 0 0 3px rgba(245,196,0,.15); }
  .form-label { display:block; font-size:.75rem; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; margin-bottom:6px; }
</style>
@endpush

@section('content')
<div class="pt-28 sm:pt-32 max-w-2xl mx-auto px-6 pb-10">
  <div class="flex items-center gap-3 mb-8">
    <a href="{{ route('profile.index') }}" class="text-gray-400 hover:text-navy-mid transition-colors">
      <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <h1 class="text-2xl font-extrabold text-navy-deep">Edit Profil</h1>
  </div>

  

  <!-- Form Profil -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <h2 class="font-bold text-navy-deep text-lg mb-5"> Informasi Akun</h2>
    @if($errors->hasAny(['nama_lengkap','email','foto_profil']))
      <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-xl mb-5 text-sm">
        @foreach(['nama_lengkap','email','foto_profil'] as $f) @if($errors->has($f))<p>{{ $errors->first($f) }}</p>@endif @endforeach
      </div>
    @endif
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
      @csrf @method('PUT')
      <div class="flex items-center gap-5">
        <img id="avatarPreview" src="{{ $user->avatar_url }}" class="w-20 h-20 rounded-2xl object-cover border-2 border-gray-200">
        <div class="flex-1">
          <label class="form-label">Foto Profil</label>
          <input type="file" name="foto_profil" accept="image/*" class="form-input px-4 py-2.5 text-sm text-gray-500" onchange="previewAvatar(this)">
          <p class="text-gray-400 text-xs mt-1">JPG, PNG, WebP. Maks 2MB.</p>
        </div>
      </div>
      <div>
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap',$user->nama_lengkap) }}" class="form-input px-4 py-3 text-sm" required>
      </div>
      <div>
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email',$user->email) }}" class="form-input px-4 py-3 text-sm" required>
      </div>
      <div>
        <label class="form-label">No. HP</label>
        <div class="flex">
          <span class="inline-flex items-center px-3 bg-gray-50 border border-r-0 border-gray-200 rounded-l-xl text-gray-500 text-sm">+62</span>
          <input type="tel" name="no_hp" value="{{ old('no_hp',$user->no_hp) }}" placeholder="812xxxxxxxx" class="form-input flex-1 px-4 py-3 text-sm" style="border-radius:0 12px 12px 0">
        </div>
      </div>

      @if($eoApplication)
      <div class="bg-slate-50 rounded-2xl border border-slate-200 p-5">
        <h3 class="font-bold text-navy-deep text-base mb-4">Data EO / Pengelola</h3>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
          <div>
            <label class="form-label">Nama Organisasi / EO</label>
            <input type="text" name="nama_organisasi" value="{{ old('nama_organisasi', $eoApplication->nama_organisasi) }}" class="form-input px-4 py-3 text-sm" required>
          </div>
          <div>
            <label class="form-label">Jenis Entitas</label>
            <select name="jenis_entitas" class="form-input px-4 py-3 text-sm" required>
              @foreach(['perorangan'=>'Perorangan','cv'=>'CV / Firma','pt'=>'PT','yayasan'=>'Yayasan / NGO','komunitas'=>'Komunitas'] as $v=>$l)
              <option value="{{ $v }}" {{ old('jenis_entitas', $eoApplication->jenis_entitas) === $v ? 'selected' : '' }}>{{ $l }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="mt-4">
          <label class="form-label">Skala Event</label>
          <select name="skala_event" class="form-input px-4 py-3 text-sm" required>
            @foreach(['kecil'=>'Kecil (<100 orang)','menengah'=>'Menengah (100-1K)','besar'=>'Besar (>1.000)'] as $v=>$l)
            <option value="{{ $v }}" {{ old('skala_event', $eoApplication->skala_event) === $v ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
          </select>
        </div>

        <div class="mt-4">
          <label class="form-label">Alamat Organisasi</label>
          <input type="text" name="alamat_organisasi" value="{{ old('alamat_organisasi', $eoApplication->alamat_organisasi) }}" class="form-input px-4 py-3 text-sm" required>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
          <div>
            <label class="form-label">No. HP Bisnis</label>
            <input type="tel" name="no_hp_bisnis" value="{{ old('no_hp_bisnis', $eoApplication->no_hp_bisnis) }}" class="form-input px-4 py-3 text-sm" required>
          </div>
          <div>
            <label class="form-label">Website / Media Sosial</label>
            <input type="text" name="website" value="{{ old('website', $eoApplication->website) }}" class="form-input px-4 py-3 text-sm">
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
          <div>
            <label class="form-label">Bank Rekening</label>
            <select name="bank" class="form-input px-4 py-3 text-sm" required>
              @foreach(['bca'=>'BCA','bni'=>'BNI','bri'=>'BRI','mandiri'=>'Mandiri','bsi'=>'BSI','cimb'=>'CIMB Niaga','lain'=>'Bank Lainnya'] as $v=>$l)
              <option value="{{ $v }}" {{ old('bank', $eoApplication->bank) === $v ? 'selected' : '' }}>{{ $l }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="form-label">No. Rekening</label>
            <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening', $eoApplication->nomor_rekening) }}" class="form-input px-4 py-3 text-sm" required>
          </div>
        </div>

        <div class="mt-4">
          <label class="form-label">Nama Pemilik Rekening</label>
          <input type="text" name="nama_rekening" value="{{ old('nama_rekening', $eoApplication->nama_rekening) }}" class="form-input px-4 py-3 text-sm" required>
        </div>

        <div class="mt-4">
          <label class="form-label">NPWP <span class="text-gray-400">(opsional)</span></label>
          <input type="text" name="npwp" value="{{ old('npwp', $eoApplication->npwp) }}" class="form-input px-4 py-3 text-sm">
        </div>

        <div class="mt-4">
          <label class="form-label">Dokumen Legalitas <span class="text-gray-400">(opsional)</span></label>
          <input type="file" name="dokumen_legalitas" accept=".pdf,.jpg,.jpeg,.png" class="form-input px-4 py-3 text-sm text-gray-500">
          @if($eoApplication->dokumen_url)
          <p class="text-xs text-gray-500 mt-2">Dokumen saat ini: <a href="{{ $eoApplication->dokumen_url }}" target="_blank" class="text-blue-600 underline">Lihat</a></p>
          @endif
        </div>
      </div>
      @endif

      <div>
        <label class="form-label">Role Akun</label>
        <div class="form-input px-4 py-3 text-sm text-gray-400 cursor-not-allowed bg-gray-50">
          {{ $user->role==='pengelola' ? ' Pengelola Event (EO)' : ($user->role==='admin' ? ' Admin' : ' Pembeli Tiket') }}
        </div>
      </div>
      <button type="submit" class="w-full bg-gold text-navy-deep font-bold py-3.5 rounded-xl hover:bg-gold-light transition-all text-sm">
         Simpan Perubahan
      </button>
    </form>
  </div>

  <!-- Ganti Password -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h2 class="font-bold text-navy-deep text-lg mb-5"> Ganti Password</h2>
    @if($errors->hasAny(['current_password','password']))
      <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-xl mb-5 text-sm">
        @foreach(['current_password','password'] as $f) @if($errors->has($f))<p>{{ $errors->first($f) }}</p>@endif @endforeach
      </div>
    @endif
    <form method="POST" action="{{ route('profile.password') }}" class="space-y-5">
      @csrf @method('PUT')
      <div>
        <label class="form-label">Password Saat Ini</label>
        <input type="password" name="current_password" placeholder="Masukkan password lama" class="form-input px-4 py-3 text-sm" required>
      </div>
      <div>
        <label class="form-label">Password Baru</label>
        <input type="password" name="password" placeholder="Minimal 8 karakter" class="form-input px-4 py-3 text-sm" required>
      </div>
      <div>
        <label class="form-label">Konfirmasi Password Baru</label>
        <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="form-input px-4 py-3 text-sm" required>
      </div>
      <button type="submit" class="w-full bg-navy-mid text-white font-bold py-3.5 rounded-xl hover:bg-navy-deep transition-all text-sm">
         Ubah Password
      </button>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function previewAvatar(input) {
    if(input.files&&input.files[0]){
      const reader=new FileReader();
      reader.onload=e=>document.getElementById('avatarPreview').src=e.target.result;
      reader.readAsDataURL(input.files[0]);
    }
  }
</script>
@endpush
