@extends('layouts.app')
@section('title', 'Pilih Tiket — ' . $event->judul)

@push('styles')
<style>
  .form-input { border: 1.5px solid #e5e7eb; border-radius: 12px; outline: none; transition: all .2s; color: #1e293b; }
  .form-input:focus { border-color: #F5C400; box-shadow: 0 0 0 3px rgba(245,196,0,.15); }
  .ticket-card { background: white; border: 1.5px solid #e5e7eb; border-radius: 16px; transition: all .2s; }
  .ticket-card:hover { border-color: #F5C400; box-shadow: 0 4px 20px rgba(0,24,64,.08); }
  .qty-btn { width:32px; height:32px; border-radius:8px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; cursor:pointer; font-weight:700; transition:all .2s; border:none; }
  .qty-btn:hover { background:#F5C400; color:#001840; }
</style>
@endpush

@section('content')
<div class="pt-24 max-w-3xl mx-auto px-6 py-10">
  <a href="{{ route('events.show', $event) }}" class="flex items-center gap-2 text-navy-mid hover:text-gold transition-colors text-sm font-semibold mb-6">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Kembali ke Detail Event
  </a>

  <div class="flex items-center gap-4 mb-8">
    <img src="{{ $event->cover_url }}" class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
    <div>
      <h1 class="text-xl font-extrabold text-navy-deep">{{ $event->judul }}</h1>
      <p class="text-gray-500 text-sm">{{ $event->tanggal_waktu->format('d M Y') }} · {{ $event->venue }}, {{ $event->lokasi_kota }}</p>
    </div>
  </div>

  @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-xl mb-6 text-sm">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="{{ route('checkout.keranjang', $event) }}">
    @csrf
    <h2 class="font-bold text-navy-deep text-base mb-4">Pilih Jumlah Tiket</h2>
    <div class="space-y-4 mb-8">
      @foreach($categories as $cat)
      <div class="ticket-card p-5">
        <div class="flex items-center justify-between">
          <div>
            <p class="font-bold text-navy-deep">{{ $cat->nama_kategori }}</p>
            <p class="text-gold font-bold text-lg mt-1">{{ $cat->harga > 0 ? 'Rp '.number_format($cat->harga,0,',','.') : 'GRATIS' }}</p>
            <p class="text-gray-400 text-xs mt-0.5">Sisa: {{ number_format($cat->kuota,0,',','.') }} tiket</p>
          </div>
          <div class="flex items-center gap-3">
            <button type="button" onclick="changeQty('{{ $cat->id }}',-1)" class="qty-btn">−</button>
            <input type="number" name="tickets[{{ $cat->id }}]" id="qty-{{ $cat->id }}"
                   class="form-input w-12 text-center font-bold py-1.5 text-sm" value="0" min="0" max="{{ $cat->kuota }}" readonly>
            <button type="button" onclick="changeQty('{{ $cat->id }}',1)" class="qty-btn">+</button>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    <div id="totalPreview" class="hidden bg-navy-mid/5 border border-navy-mid/10 rounded-xl p-4 mb-6">
      <div class="flex justify-between text-sm text-gray-600 mb-1">
        <span>Total Tiket</span><span id="previewQty">0</span>
      </div>
      <div class="flex justify-between font-bold text-navy-deep">
        <span>Subtotal</span><span id="previewTotal" class="text-gold">Rp 0</span>
      </div>
    </div>

    <button type="submit" class="w-full bg-gold text-navy-deep font-bold py-4 rounded-xl hover:bg-gold-light transition-all text-sm hover:shadow-lg hover:shadow-gold/30">
      Lanjut ke Checkout →
    </button>
  </form>
</div>
@endsection

@push('scripts')
<script>
  const prices = { @foreach($categories as $cat)'{{ $cat->id }}': {{ $cat->harga }},@endforeach };
  function changeQty(id, d) {
    const inp = document.getElementById('qty-'+id);
    const current = +inp.value;
    const target = Math.max(0, Math.min(+inp.getAttribute('max'), current + d));
    const qtyTotal = Object.keys(prices).reduce((sum, key) => sum + +document.getElementById('qty-'+key).value, 0);
    if (d > 0 && qtyTotal >= 10) {
      alert('Maksimal 10 tiket per pesanan.');
      return;
    }

    if (d > 0 && qtyTotal + 1 > 10) {
      alert('Maksimal 10 tiket per pesanan.');
      return;
    }

    inp.value = target;
    updateTotal();
  }
  function updateTotal() {
    let total=0, qty=0;
    Object.keys(prices).forEach(id => { const v=+document.getElementById('qty-'+id).value; total+=v*prices[id]; qty+=v; });
    const preview = document.getElementById('totalPreview');
    if(qty>0){ preview.classList.remove('hidden'); document.getElementById('previewQty').textContent=qty+' tiket'; document.getElementById('previewTotal').textContent='Rp '+total.toLocaleString('id-ID'); }
    else preview.classList.add('hidden');
  }
</script>
@endpush
