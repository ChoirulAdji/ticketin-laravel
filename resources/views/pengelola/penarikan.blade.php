@extends('layouts.pengelola')
@section('title', 'Tarik Pendapatan')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
  <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
      <h1 class="text-2xl font-extrabold text-navy-deep">Tarik Pendapatan</h1>
      <p class="text-sm text-gray-500 mt-0.5">Ajukan pencairan pendapatan bersih EO ke rekening tujuan.</p>
    </div>
  </div>

  @if(session('success'))
  <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm mb-5">{{ session('success') }}</div>
  @endif

  @if(session('error'))
  <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-5">{{ session('error') }}</div>
  @endif

  @if($errors->any())
  <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-5">{{ $errors->first() }}</div>
  @endif

  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach([
      ['label' => 'Pendapatan Bersih', 'value' => $summary['total_pendapatan'], 'color' => 'text-navy-deep'],
      ['label' => 'Menunggu Proses', 'value' => $summary['pending'], 'color' => 'text-amber-600'],
      ['label' => 'Sudah Ditarik', 'value' => $summary['processed'], 'color' => 'text-green-600'],
      ['label' => 'Saldo Tersedia', 'value' => $summary['saldo_tersedia'], 'color' => 'text-navy-mid'],
    ] as $card)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
      <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ $card['label'] }}</p>
      <p class="text-2xl font-extrabold mt-1 {{ $card['color'] }}">Rp {{ number_format($card['value'], 0, ',', '.') }}</p>
    </div>
    @endforeach
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
      <h2 class="font-bold text-navy-deep mb-1">Ajukan Penarikan</h2>
      <p class="text-xs text-gray-500 mb-5">Minimal penarikan Rp 10.000. Dana pending akan mengurangi saldo tersedia.</p>

      <form method="POST" action="{{ route('pengelola.penarikan.store') }}" class="space-y-4">
        @csrf
        <div>
          <label class="text-xs font-semibold text-gray-500 block mb-1">Nominal Penarikan</label>
          <input type="number" name="amount" min="10000" max="{{ floor($summary['saldo_tersedia']) }}" value="{{ old('amount') }}"
                 class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-navy-mid"
                 placeholder="Contoh: 250000">
        </div>

        <div class="bg-navy-mid/5 border border-navy-mid/10 rounded-xl p-4 text-sm">
          <p class="text-gray-500 text-xs mb-1">Rekening tujuan aktif</p>
          @if($application && $application->bank && $application->nomor_rekening && $application->nama_rekening)
            <p class="font-bold text-navy-deep">{{ strtoupper($application->bank) }} - {{ $application->nomor_rekening }}</p>
            <p class="text-gray-500 text-xs mt-0.5">{{ $application->nama_rekening }}</p>
          @else
            <p class="text-red-500 font-semibold">Rekening belum lengkap.</p>
          @endif
        </div>

        <button type="submit"
                class="w-full bg-gold text-navy-deep font-bold py-3 rounded-xl hover:bg-gold-light transition disabled:opacity-50"
                {{ $summary['saldo_tersedia'] < 10000 ? 'disabled' : '' }}>
          Ajukan Penarikan
        </button>
      </form>
    </div>

    <div class="lg:col-span-3 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
      <h2 class="font-bold text-navy-deep mb-1">Rekening Tujuan</h2>
      <p class="text-xs text-gray-500 mb-5">Perubahan rekening hanya berlaku untuk pengajuan penarikan berikutnya.</p>

      <form method="POST" action="{{ route('pengelola.rekening.update') }}">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="text-xs font-semibold text-gray-500 block mb-1">Bank</label>
            <select name="bank" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-navy-mid">
              <option value="">Pilih bank</option>
              @foreach($banks as $value => $label)
              <option value="{{ $value }}" {{ old('bank', $application->bank ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="text-xs font-semibold text-gray-500 block mb-1">Nomor Rekening</label>
            <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening', $application->nomor_rekening ?? '') }}"
                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-navy-mid"
                   placeholder="Nomor rekening">
          </div>
          <div>
            <label class="text-xs font-semibold text-gray-500 block mb-1">Nama Pemilik</label>
            <input type="text" name="nama_rekening" value="{{ old('nama_rekening', $application->nama_rekening ?? '') }}"
                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-navy-mid"
                   placeholder="Nama sesuai rekening">
          </div>
        </div>
        <div class="flex justify-end mt-4">
          <button type="submit" class="bg-navy-mid text-white font-bold px-5 py-2.5 rounded-xl hover:bg-navy-deep transition text-sm">
            Simpan Rekening
          </button>
        </div>
      </form>
    </div>
  </div>

  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
      <h2 class="font-bold text-navy-deep">Riwayat Penarikan</h2>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="bg-gray-50 text-gray-500">
            <th class="px-4 py-3 text-left text-xs font-bold uppercase">Tanggal</th>
            <th class="px-4 py-3 text-right text-xs font-bold uppercase">Nominal</th>
            <th class="px-4 py-3 text-left text-xs font-bold uppercase">Rekening</th>
            <th class="px-4 py-3 text-left text-xs font-bold uppercase">Status</th>
            <th class="px-4 py-3 text-left text-xs font-bold uppercase">Catatan</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($withdrawals as $withdrawal)
          <tr class="hover:bg-gray-50 transition">
            <td class="px-4 py-3 text-xs text-gray-500">{{ $withdrawal->created_at->format('d/m/Y H:i') }}</td>
            <td class="px-4 py-3 text-right font-bold text-navy-deep">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</td>
            <td class="px-4 py-3">
              <p class="font-semibold text-gray-700 text-xs">{{ strtoupper($withdrawal->bank) }} - {{ $withdrawal->nomor_rekening }}</p>
              <p class="text-gray-400 text-xs">{{ $withdrawal->nama_rekening }}</p>
            </td>
            <td class="px-4 py-3">
              <span class="text-xs font-bold px-2 py-0.5 rounded-full
                {{ $withdrawal->status === 'processed' ? 'bg-green-100 text-green-700' :
                   ($withdrawal->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-600') }}">
                {{ $withdrawal->status === 'processed' ? 'Diproses' : ($withdrawal->status === 'pending' ? 'Pending' : 'Ditolak') }}
              </span>
            </td>
            <td class="px-4 py-3 text-xs text-gray-500">{{ $withdrawal->catatan ?: '-' }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="px-4 py-12 text-center text-gray-400">Belum ada penarikan.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-4 py-4 border-t border-gray-100">{{ $withdrawals->links() }}</div>
  </div>
</div>
@endsection
