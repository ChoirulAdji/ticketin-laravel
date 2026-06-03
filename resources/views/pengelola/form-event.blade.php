@extends('layouts.pengelola')
@section('title', $event ? 'Edit Event' : 'Tambah Event')

@push('styles')
<style>
  .form-input { border:1.5px solid #e5e7eb; border-radius:12px; outline:none; transition:all .2s; color:#1e293b; background:white; width:100%; }
  .form-input:focus { border-color:#F5C400; box-shadow:0 0 0 3px rgba(245,196,0,.15); }
  .form-input::placeholder { color:#9ca3af; }
  .form-label { display:block; font-size:.75rem; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; margin-bottom:6px; }
  .item-row { background:#f8fafc; border:1px solid #e5e7eb; border-radius:12px; padding:14px; }
  .add-btn { font-size:.75rem; background:#fffbeb; color:#b45309; border:1px solid #fde68a; padding:6px 14px; border-radius:8px; cursor:pointer; transition:all .2s; }
  .add-btn:hover { background:#fef3c7; }
  .remove-btn { color:#ef4444; cursor:pointer; flex-shrink:0; transition:color .2s; background:none; border:none; }
  .remove-btn:hover { color:#dc2626; }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto px-6 py-10">
  <div class="flex items-center gap-3 mb-8">
    <a href="{{ route('pengelola.dashboard') }}" class="text-gray-400 hover:text-navy-mid transition-colors">
      <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <h1 class="text-2xl font-extrabold text-navy-deep">{{ $event ? 'Edit Event' : 'Tambah Event Baru' }}</h1>
  </div>

  @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl mb-6 text-sm">
      <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <form method="POST" action="{{ $event ? route('pengelola.event.update',$event) : route('pengelola.event.store') }}"
        enctype="multipart/form-data" class="space-y-6">
    @csrf @if($event) @method('PUT') @endif

    <!-- Info Dasar -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
      <h2 class="font-bold text-navy-deep text-lg">📋 Informasi Event</h2>
      <div><label class="form-label">Judul Event</label><input type="text" name="judul" value="{{ old('judul',$event->judul??'') }}" placeholder="Nama event kamu" class="form-input px-4 py-3 text-sm" required></div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div><label class="form-label">Kategori</label>
          <select name="kategori" class="form-input px-4 py-3 text-sm">
            @foreach(['Konser','Festival','Seminar','Workshop','Olahraga','Seni','Teknologi','Lainnya'] as $kat)
              <option value="{{ $kat }}" {{ old('kategori',$event->kategori??'')===$kat?'selected':'' }}>{{ $kat }}</option>
            @endforeach
          </select>
        </div>
        <div><label class="form-label">Status</label>
          <select name="status" class="form-input px-4 py-3 text-sm">
            @foreach(['draft'=>'📝 Draft','published'=>'🟢 Published','cancelled'=>'❌ Cancelled'] as $v=>$l)
              <option value="{{ $v }}" {{ old('status',$event->status??'draft')===$v?'selected':'' }}>{{ $l }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div><label class="form-label">Tanggal</label><input type="date" name="tanggal" value="{{ old('tanggal',$event?$event->tanggal_waktu->format('Y-m-d'):'') }}" class="form-input px-4 py-3 text-sm" required></div>
        <div><label class="form-label">Jam Mulai</label><input type="time" name="waktu" value="{{ old('waktu',$event?$event->tanggal_waktu->format('H:i'):'') }}" class="form-input px-4 py-3 text-sm" required></div>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div><label class="form-label">Kota</label><input type="text" name="lokasi_kota" value="{{ old('lokasi_kota',$event->lokasi_kota??'') }}" placeholder="Surabaya" class="form-input px-4 py-3 text-sm" required></div>
        <div><label class="form-label">Venue / Tempat</label><input type="text" name="venue" value="{{ old('venue',$event->venue??'') }}" placeholder="Nama gedung / lokasi" class="form-input px-4 py-3 text-sm" required></div>
      </div>
      <div><label class="form-label">Deskripsi Event</label><textarea name="deskripsi" rows="5" placeholder="Ceritakan tentang event kamu..." class="form-input px-4 py-3 text-sm resize-none">{{ old('deskripsi',$event->deskripsi??'') }}</textarea></div>
      {{-- ── COVER UPLOAD ─────────────────────────────────────── --}}
      <div>
        <label class="form-label">Gambar Cover <span class="text-gray-400 font-normal text-xs">(Rasio 16:9, min 1200×630px)</span></label>

        {{-- Drop zone --}}
        <div id="cover-dropzone"
             class="relative border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50 hover:border-navy-mid hover:bg-blue-50/30 transition-all cursor-pointer overflow-hidden"
             style="min-height:220px;"
             onclick="document.getElementById('cover-file-input').click()"
             ondragover="event.preventDefault();this.classList.add('border-navy-mid','bg-blue-50/50')"
             ondragleave="this.classList.remove('border-navy-mid','bg-blue-50/50')"
             ondrop="handleCoverDrop(event)">

          {{-- Current / preview image --}}
          <div id="cover-preview-wrap" class="{{ ($event && $event->gambar_cover) ? '' : 'hidden' }} absolute inset-0">
            <img id="cover-preview-img"
                 src="{{ $event && $event->gambar_cover ? $event->cover_url : '' }}"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
              <button type="button" onclick="event.stopPropagation();document.getElementById('cover-file-input').click()"
                class="bg-white text-navy-mid font-semibold text-xs px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                Ganti Foto
              </button>
              <button type="button" onclick="event.stopPropagation();openCropper()"
                class="bg-gold text-navy-deep font-semibold text-xs px-4 py-2 rounded-lg hover:bg-gold-light transition">
                ✂️ Crop
              </button>
            </div>
          </div>

          {{-- Empty state --}}
          <div id="cover-empty-state" class="{{ ($event && $event->gambar_cover) ? 'hidden' : '' }} flex flex-col items-center justify-center h-full py-12 pointer-events-none">
            <div class="w-14 h-14 bg-navy-mid/10 rounded-2xl flex items-center justify-center mb-3">
              <svg class="w-7 h-7 text-navy-mid" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <p class="font-semibold text-navy-mid text-sm">Klik atau drag foto ke sini</p>
            <p class="text-gray-400 text-xs mt-1">JPG, PNG, WebP — maks 5MB</p>
          </div>
        </div>

        <input type="file" id="cover-file-input" name="gambar_cover" accept="image/*" class="hidden" onchange="onCoverFileSelected(this)">
        <input type="hidden" name="cover_cropped" id="cover-cropped-data">

        {{-- Crop actions (visible after image selected) --}}
        <div id="cover-actions" class="{{ ($event && $event->gambar_cover) ? '' : 'hidden' }} flex items-center gap-2 mt-2">
          <button type="button" onclick="openCropper()"
            class="flex items-center gap-1.5 text-xs font-semibold text-navy-mid bg-navy-mid/10 hover:bg-navy-mid/20 px-3 py-1.5 rounded-lg transition">
            ✂️ Crop & Resize
          </button>
          <button type="button" onclick="removeCover()"
            class="flex items-center gap-1.5 text-xs font-semibold text-red-500 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition">
            🗑 Hapus
          </button>
          <span id="cover-crop-badge" class="hidden text-xs text-green-600 font-semibold bg-green-50 px-2 py-1 rounded-lg">✅ Sudah di-crop (1200×630)</span>
        </div>
      </div>

      {{-- ── CROPPER MODAL ─────────────────────────────────────── --}}
      <div id="cropper-modal" class="fixed inset-0 z-[70] hidden bg-black/80 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-2xl overflow-hidden shadow-2xl">
          <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-bold text-navy-deep text-sm">✂️ Crop Foto Cover</h3>
            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-lg">Rasio 16:9</span>
          </div>
          <div class="p-4 bg-gray-900 overflow-hidden" style="height:340px;">
            <canvas id="crop-canvas" class="max-w-full max-h-full mx-auto block" style="cursor:crosshair;"></canvas>
          </div>
          {{-- Crop controls --}}
          <div class="p-4 space-y-3">
            <div class="flex items-center gap-3">
              <label class="text-xs font-semibold text-gray-600 w-20">Zoom</label>
              <input type="range" id="crop-zoom" min="0.5" max="3" step="0.05" value="1"
                class="flex-1 accent-navy-mid" oninput="drawCropPreview()">
              <span id="crop-zoom-val" class="text-xs text-gray-500 w-8">1×</span>
            </div>
            <p class="text-xs text-gray-400 text-center">Geser gambar untuk memilih area</p>
          </div>
          <div class="flex gap-3 px-5 py-4 border-t border-gray-100">
            <button type="button" onclick="closeCropper()"
              class="flex-1 border border-gray-200 text-gray-600 font-semibold text-sm py-2.5 rounded-xl hover:bg-gray-50 transition">
              Batal
            </button>
            <button type="button" onclick="applyCrop()"
              class="flex-1 bg-navy-mid text-white font-bold text-sm py-2.5 rounded-xl hover:bg-navy-deep transition">
              ✅ Terapkan Crop
            </button>
          </div>
        </div>
      </div>

      {{-- ── GALLERY UPLOAD ────────────────────────────────────── --}}
      <div>
        <label class="form-label">Foto Gallery <span class="text-gray-400 font-normal text-xs">(Opsional, maks 8 foto)</span></label>
        <div id="gallery-grid" class="grid grid-cols-3 sm:grid-cols-4 gap-3 mb-3">

          {{-- Existing gallery images --}}
          @if($event && $event->galleries->isNotEmpty())
            @foreach($event->galleries as $gal)
            <div class="gallery-item relative aspect-square rounded-xl overflow-hidden bg-gray-100 group" data-gallery-id="{{ $gal->id }}">
              <input type="hidden" name="gallery_keep_ids[]" value="{{ $gal->id }}">
              <img src="{{ $gal->url }}" class="w-full h-full object-cover">
              <button type="button" onclick="removeGalleryExisting(this)"
                class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full text-xs font-bold flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow">×</button>
            </div>
            @endforeach
          @endif

          {{-- Add photo button --}}
          <div id="gallery-add-btn"
               class="aspect-square rounded-xl border-2 border-dashed border-gray-200 hover:border-navy-mid bg-gray-50 hover:bg-blue-50/30 flex flex-col items-center justify-center cursor-pointer transition"
               onclick="document.getElementById('gallery-file-input').click()">
            <svg class="w-6 h-6 text-gray-400 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span class="text-xs text-gray-400 font-medium">Tambah</span>
          </div>
        </div>
        <input type="file" id="gallery-file-input" accept="image/*" multiple class="hidden" onchange="onGalleryFilesSelected(this)">
        <p class="text-xs text-gray-400">Foto gallery akan ditampilkan di halaman detail event.</p>
      </div>
    </div>

    <!-- Kategori Tiket -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
      <div class="flex items-center justify-between mb-5">
        <h2 class="font-bold text-navy-deep text-lg">🎟️ Kategori Tiket</h2>
        <button type="button" class="add-btn" onclick="tambahTicket()">+ Tambah Tiket</button>
      </div>
      <div id="ticketContainer" class="space-y-3">
        @if($event && $event->ticketCategories->isNotEmpty())
          @foreach($event->ticketCategories as $cat)
          <div class="item-row flex gap-3 items-center">
            <input type="text" name="nama_kategori[]" value="{{ $cat->nama_kategori }}" placeholder="Nama kategori" class="form-input flex-1 px-3 py-2.5 text-sm">
            <input type="number" name="harga[]" value="{{ $cat->harga }}" placeholder="Harga (Rp)" class="form-input w-36 px-3 py-2.5 text-sm">
            <input type="number" name="kuota[]" value="{{ $cat->kuota }}" placeholder="Kuota" class="form-input w-28 px-3 py-2.5 text-sm">
            <button type="button" onclick="hapus(this)" class="remove-btn"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
          </div>
          @endforeach
        @else
          <div class="item-row flex gap-3 items-center">
            <input type="text" name="nama_kategori[]" placeholder="Nama (misal: Regular)" class="form-input flex-1 px-3 py-2.5 text-sm">
            <input type="number" name="harga[]" placeholder="Harga (Rp)" class="form-input w-36 px-3 py-2.5 text-sm">
            <input type="number" name="kuota[]" placeholder="Kuota" class="form-input w-28 px-3 py-2.5 text-sm">
            <button type="button" onclick="hapus(this)" class="remove-btn"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
          </div>
        @endif
      </div>
    </div>

    <!-- Line-up -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
      <div class="flex items-center justify-between mb-5">
        <h2 class="font-bold text-navy-deep text-lg">🎤 Line-up</h2>
        <button type="button" class="add-btn" onclick="tambahLineup()">+ Tambah</button>
      </div>
      <div id="lineupContainer" class="space-y-3">
        @if($event && $event->lineups->isNotEmpty())
          @foreach($event->lineups as $i => $lu)
          <div class="item-row flex gap-3 items-center">
            <input type="text" name="lineup_nama[]" value="{{ $lu->nama }}" placeholder="Nama artis / pembicara" class="form-input flex-1 px-3 py-2.5 text-sm">
            <label class="flex items-center gap-2 cursor-pointer flex-shrink-0">
              <input type="checkbox" name="lineup_headliner[{{ $i }}]" value="1" {{ $lu->is_headliner?'checked':'' }} class="accent-yellow-400">
              <span class="text-gray-600 text-xs whitespace-nowrap">Headliner</span>
            </label>
            <button type="button" onclick="hapus(this)" class="remove-btn"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
          </div>
          @endforeach
        @else
          <p class="text-gray-400 text-sm text-center py-3 border border-dashed border-gray-200 rounded-xl" id="lineupPlaceholder">Belum ada line-up. Klik + untuk menambahkan.</p>
        @endif
      </div>
    </div>

    <!-- FAQ -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
      <div class="flex items-center justify-between mb-5">
        <h2 class="font-bold text-navy-deep text-lg">❓ FAQ</h2>
        <button type="button" class="add-btn" onclick="tambahFaq()">+ Tambah FAQ</button>
      </div>
      <div id="faqContainer" class="space-y-3">
        @if($event && $event->faqs->isNotEmpty())
          @foreach($event->faqs as $faq)
          <div class="item-row">
            <div class="flex gap-3 items-start">
              <div class="flex-1 space-y-2">
                <input type="text" name="faq_pertanyaan[]" value="{{ $faq->pertanyaan }}" placeholder="Pertanyaan..." class="form-input px-3 py-2.5 text-sm">
                <textarea name="faq_jawaban[]" rows="2" placeholder="Jawaban..." class="form-input px-3 py-2.5 text-sm resize-none">{{ $faq->jawaban }}</textarea>
              </div>
              <button type="button" onclick="hapus(this)" class="remove-btn mt-2"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
          </div>
          @endforeach
        @else
          <p class="text-gray-400 text-sm text-center py-3 border border-dashed border-gray-200 rounded-xl" id="faqPlaceholder">Belum ada FAQ. Klik + untuk menambahkan.</p>
        @endif
      </div>
    </div>

    <!-- Submit -->
    <div class="flex gap-3">
      <button type="submit" class="flex-1 bg-gold text-navy-deep font-bold py-4 rounded-xl hover:bg-gold-light transition-all text-sm shadow-sm">
        {{ $event ? '💾 Simpan Perubahan' : '🚀 Buat Event' }}
      </button>
      <a href="{{ route('pengelola.dashboard') }}" class="bg-gray-100 text-gray-700 font-semibold px-6 py-4 rounded-xl hover:bg-gray-200 transition-all text-sm">Batal</a>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
  let lineupIdx = {{ $event ? $event->lineups->count() : 0 }};
  const rmIcon = `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>`;
  function hapus(btn) { btn.closest('.item-row').remove(); }
  function tambahTicket() {
    document.getElementById('ticketContainer').insertAdjacentHTML('beforeend',`
      <div class="item-row flex gap-3 items-center">
        <input type="text" name="nama_kategori[]" placeholder="Nama (misal: VIP)" class="form-input flex-1 px-3 py-2.5 text-sm">
        <input type="number" name="harga[]" placeholder="Harga (Rp)" class="form-input w-36 px-3 py-2.5 text-sm">
        <input type="number" name="kuota[]" placeholder="Kuota" class="form-input w-28 px-3 py-2.5 text-sm">
        <button type="button" onclick="hapus(this)" class="remove-btn">${rmIcon}</button>
      </div>`);
  }
  function tambahLineup() {
    const ph=document.getElementById('lineupPlaceholder'); if(ph) ph.remove();
    const i=lineupIdx++;
    document.getElementById('lineupContainer').insertAdjacentHTML('beforeend',`
      <div class="item-row flex gap-3 items-center">
        <input type="text" name="lineup_nama[]" placeholder="Nama artis / pembicara" class="form-input flex-1 px-3 py-2.5 text-sm">
        <label class="flex items-center gap-2 cursor-pointer flex-shrink-0">
          <input type="checkbox" name="lineup_headliner[${i}]" value="1" class="accent-yellow-400">
          <span class="text-gray-600 text-xs whitespace-nowrap">Headliner</span>
        </label>
        <button type="button" onclick="hapus(this)" class="remove-btn">${rmIcon}</button>
      </div>`);
  }
  function tambahFaq() {
    const ph=document.getElementById('faqPlaceholder'); if(ph) ph.remove();
    document.getElementById('faqContainer').insertAdjacentHTML('beforeend',`
      <div class="item-row">
        <div class="flex gap-3 items-start">
          <div class="flex-1 space-y-2">
            <input type="text" name="faq_pertanyaan[]" placeholder="Pertanyaan..." class="form-input px-3 py-2.5 text-sm">
            <textarea name="faq_jawaban[]" rows="2" placeholder="Jawaban..." class="form-input px-3 py-2.5 text-sm resize-none"></textarea>
          </div>
          <button type="button" onclick="hapus(this)" class="remove-btn mt-2">${rmIcon}</button>
        </div>
      </div>`);
  }
  // ─── COVER UPLOAD & CROP ──────────────────────────────────────────
  let cropSourceImage = null;   // HTMLImageElement of original
  let cropOffsetX = 0, cropOffsetY = 0;
  let cropDragStart = null;
  const COVER_W = 1200, COVER_H = 630;

  function loadImageFromFile(file, cb) {
    const reader = new FileReader();
    reader.onload = e => {
      const img = new Image();
      img.onload = () => cb(img);
      img.src = e.target.result;
    };
    reader.readAsDataURL(file);
  }

  function onCoverFileSelected(input) {
    if (!input.files || !input.files[0]) return;
    loadImageFromFile(input.files[0], img => {
      cropSourceImage = img;
      cropOffsetX = 0; cropOffsetY = 0;
      document.getElementById('crop-zoom').value = 1;
      // Show preview immediately (uncropped)
      showCoverPreview(img.src);
      // Auto-open cropper
      openCropper();
    });
  }

  function handleCoverDrop(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('border-navy-mid','bg-blue-50/50');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
      document.getElementById('cover-file-input').files; // can't set, use workaround
      loadImageFromFile(file, img => {
        cropSourceImage = img;
        cropOffsetX = 0; cropOffsetY = 0;
        document.getElementById('crop-zoom').value = 1;
        showCoverPreview(img.src);
        openCropper();
      });
    }
  }

  function showCoverPreview(src) {
    document.getElementById('cover-preview-img').src = src;
    document.getElementById('cover-preview-wrap').classList.remove('hidden');
    document.getElementById('cover-empty-state').classList.add('hidden');
    document.getElementById('cover-actions').classList.remove('hidden');
  }

  function removeCover() {
    document.getElementById('cover-preview-wrap').classList.add('hidden');
    document.getElementById('cover-empty-state').classList.remove('hidden');
    document.getElementById('cover-actions').classList.add('hidden');
    document.getElementById('cover-cropped-data').value = '';
    document.getElementById('cover-crop-badge').classList.add('hidden');
    document.getElementById('cover-file-input').value = '';
    cropSourceImage = null;
  }

  function openCropper() {
    if (!cropSourceImage) return;
    document.getElementById('cropper-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    drawCropPreview();
  }

  function closeCropper() {
    document.getElementById('cropper-modal').classList.add('hidden');
    document.body.style.overflow = '';
  }

  function drawCropPreview() {
    if (!cropSourceImage) return;
    const canvas = document.getElementById('crop-canvas');
    const zoom   = parseFloat(document.getElementById('crop-zoom').value);
    document.getElementById('crop-zoom-val').textContent = zoom.toFixed(1) + '×';

    // Display at 16:9 within 600px wide
    const dispW = Math.min(600, canvas.parentElement.clientWidth - 32);
    const dispH = Math.round(dispW * 630 / 1200);
    canvas.width  = dispW;
    canvas.height = dispH;

    const ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, dispW, dispH);

    // Scale source to fit in crop area with zoom
    const scaleBase = Math.max(dispW / cropSourceImage.width, dispH / cropSourceImage.height);
    const scale = scaleBase * zoom;
    const sw = cropSourceImage.width  * scale;
    const sh = cropSourceImage.height * scale;

    // Clamp offsets
    cropOffsetX = Math.max(Math.min(cropOffsetX, 0), dispW - sw);
    cropOffsetY = Math.max(Math.min(cropOffsetY, 0), dispH - sh);

    ctx.drawImage(cropSourceImage, cropOffsetX, cropOffsetY, sw, sh);

    // Grid overlay
    ctx.strokeStyle = 'rgba(255,255,255,0.25)';
    ctx.lineWidth = 1;
    for (let i = 1; i < 3; i++) {
      ctx.beginPath(); ctx.moveTo(dispW*i/3, 0); ctx.lineTo(dispW*i/3, dispH); ctx.stroke();
      ctx.beginPath(); ctx.moveTo(0, dispH*i/3); ctx.lineTo(dispW, dispH*i/3); ctx.stroke();
    }
  }

  // Drag to pan
  const cropCanvas = document.getElementById('crop-canvas');
  cropCanvas.addEventListener('mousedown',  e => { cropDragStart = {x: e.clientX - cropOffsetX, y: e.clientY - cropOffsetY}; });
  cropCanvas.addEventListener('mousemove',  e => { if (!cropDragStart) return; cropOffsetX = e.clientX - cropDragStart.x; cropOffsetY = e.clientY - cropDragStart.y; drawCropPreview(); });
  cropCanvas.addEventListener('mouseup',    () => cropDragStart = null);
  cropCanvas.addEventListener('mouseleave', () => cropDragStart = null);
  // Touch support
  cropCanvas.addEventListener('touchstart', e => { const t=e.touches[0]; cropDragStart={x:t.clientX-cropOffsetX,y:t.clientY-cropOffsetY}; });
  cropCanvas.addEventListener('touchmove',  e => { e.preventDefault(); if(!cropDragStart)return; const t=e.touches[0]; cropOffsetX=t.clientX-cropDragStart.x; cropOffsetY=t.clientY-cropDragStart.y; drawCropPreview(); });
  cropCanvas.addEventListener('touchend',   () => cropDragStart=null);

  function applyCrop() {
    if (!cropSourceImage) return;
    const zoom = parseFloat(document.getElementById('crop-zoom').value);

    // Render final crop at full resolution 1200×630
    const out = document.createElement('canvas');
    out.width  = COVER_W;
    out.height = COVER_H;
    const ctx = out.getContext('2d');

    const dispW = document.getElementById('crop-canvas').width;
    const dispH = document.getElementById('crop-canvas').height;
    const scaleBase = Math.max(dispW / cropSourceImage.width, dispH / cropSourceImage.height);
    const scale = scaleBase * zoom;

    // Map display offset back to source coordinates
    const srcX = -cropOffsetX / scale;
    const srcY = -cropOffsetY / scale;
    const srcW =  dispW / scale;
    const srcH =  dispH / scale;

    ctx.drawImage(cropSourceImage, srcX, srcY, srcW, srcH, 0, 0, COVER_W, COVER_H);

    const dataUrl = out.toDataURL('image/jpeg', 0.88);
    document.getElementById('cover-cropped-data').value = dataUrl;
    document.getElementById('cover-preview-img').src    = dataUrl;
    document.getElementById('cover-crop-badge').classList.remove('hidden');

    showCoverPreview(dataUrl);
    closeCropper();
  }

  // ─── GALLERY UPLOAD ────────────────────────────────────────────────
  let galleryQueue = [];    // {file, dataUrl, croppedDataUrl}
  const MAX_GALLERY = 8;

  function onGalleryFilesSelected(input) {
    if (!input.files) return;
    const existingCount = document.querySelectorAll('.gallery-item').length;
    const canAdd = MAX_GALLERY - existingCount;
    const files = Array.from(input.files).slice(0, canAdd);
    files.forEach(file => addGalleryFile(file));
    input.value = ''; // reset
  }

  function addGalleryFile(file) {
    const reader = new FileReader();
    reader.onload = e => {
      const dataUrl = e.target.result;
      const idx = galleryQueue.length;
      galleryQueue.push({ dataUrl });

      const grid = document.getElementById('gallery-grid');
      const addBtn = document.getElementById('gallery-add-btn');

      const div = document.createElement('div');
      div.className = 'gallery-item relative aspect-square rounded-xl overflow-hidden bg-gray-100 group';
      div.dataset.queueIdx = idx;
      div.innerHTML = `
        <img src="${dataUrl}" class="w-full h-full object-cover" id="gal-thumb-${idx}">
        <input type="hidden" name="gallery_cropped[]" id="gal-data-${idx}" value="${dataUrl}">
        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-1">
          <button type="button" onclick="openGalleryCropper(${idx})"
            class="bg-gold text-navy-deep text-xs font-bold px-2 py-1 rounded-lg">✂️</button>
          <button type="button" onclick="removeGalleryNew(this,${idx})"
            class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-lg">✕</button>
        </div>`;
      grid.insertBefore(div, addBtn);

      // Hide add btn if max reached
      const total = document.querySelectorAll('.gallery-item').length;
      if (total >= MAX_GALLERY) document.getElementById('gallery-add-btn').classList.add('hidden');
    };
    reader.readAsDataURL(file);
  }

  function removeGalleryExisting(btn) {
    const item = btn.closest('.gallery-item');
    item.querySelector('input[name="gallery_keep_ids[]"]').remove(); // unset keep
    item.remove();
    document.getElementById('gallery-add-btn').classList.remove('hidden');
  }

  function removeGalleryNew(btn, idx) {
    btn.closest('.gallery-item').remove();
    galleryQueue[idx] = null;
    document.getElementById('gallery-add-btn').classList.remove('hidden');
  }

  // Simple gallery crop (same crop mechanism, lighter)
  let galCropIdx = -1;
  function openGalleryCropper(idx) {
    const src = galleryQueue[idx]?.dataUrl;
    if (!src) return;
    galCropIdx = idx;
    loadImageFromDataUrl(src, img => {
      cropSourceImage = img;
      cropOffsetX = 0; cropOffsetY = 0;
      document.getElementById('crop-zoom').value = 1;
      openCropper();
    });
  }

  function loadImageFromDataUrl(dataUrl, cb) {
    const img = new Image();
    img.onload = () => cb(img);
    img.src = dataUrl;
  }

  // Override applyCrop to handle gallery mode
  const _applyCrop = applyCrop;
  window.applyCrop = function () {
    if (galCropIdx >= 0) {
      // Gallery crop mode
      const zoom = parseFloat(document.getElementById('crop-zoom').value);
      const out = document.createElement('canvas');
      out.width = 1200; out.height = 900; // 4:3 for gallery
      const ctx = out.getContext('2d');
      const dispW = document.getElementById('crop-canvas').width;
      const dispH = document.getElementById('crop-canvas').height;
      const scaleBase = Math.max(dispW / cropSourceImage.width, dispH / cropSourceImage.height);
      const scale = scaleBase * zoom;
      const srcX = -cropOffsetX / scale;
      const srcY = -cropOffsetY / scale;
      ctx.drawImage(cropSourceImage, srcX, srcY, dispW/scale, dispH/scale, 0, 0, out.width, out.height);
      const dataUrl = out.toDataURL('image/jpeg', 0.85);

      galleryQueue[galCropIdx] = { ...galleryQueue[galCropIdx], dataUrl };
      const thumb = document.getElementById('gal-thumb-' + galCropIdx);
      const hidden = document.getElementById('gal-data-' + galCropIdx);
      if (thumb) thumb.src = dataUrl;
      if (hidden) hidden.value = dataUrl;

      galCropIdx = -1;
      cropSourceImage = null;
      closeCropper();
    } else {
      _applyCrop();
    }
  };

</script>
@endpush
