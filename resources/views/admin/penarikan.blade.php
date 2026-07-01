@extends('layouts.admin')
@section('title', 'Penarikan EO')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-extrabold text-navy-deep">Penarikan EO</h1>
      <p class="text-gray-500 text-sm mt-1">Kelola permintaan tarik saldo EO yang masuk.</p>
    </div>
    <div class="text-sm text-gray-500">Total permintaan: <strong>{{ $withdrawals->total() }}</strong></div>
  </div>

  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="bg-gray-50 text-gray-500">
            <th class="px-4 py-3 text-left text-xs font-bold uppercase">Tanggal</th>
            <th class="px-4 py-3 text-right text-xs font-bold uppercase">Nominal</th>
            <th class="px-4 py-3 text-left text-xs font-bold uppercase">EO</th>
            <th class="px-4 py-3 text-left text-xs font-bold uppercase">Rekening</th>
            <th class="px-4 py-3 text-left text-xs font-bold uppercase">Status</th>
            <th class="px-4 py-3 text-left text-xs font-bold uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($withdrawals as $withdrawal)
          <tr class="hover:bg-gray-50 transition">
            <td class="px-4 py-3 text-xs text-gray-500">{{ $withdrawal->created_at->format('d/m/Y H:i') }}</td>
            <td class="px-4 py-3 text-right font-bold text-navy-deep">Rp {{ number_format($withdrawal->amount,0,',','.') }}</td>
            <td class="px-4 py-3">
              <p class="font-semibold text-gray-700 text-xs">{{ $withdrawal->pengelola->nama_lengkap ?? '-' }}</p>
              <p class="text-gray-400 text-xs">{{ $withdrawal->pengelola->email ?? '-' }}</p>
            </td>
            <td class="px-4 py-3">
              <p class="font-semibold text-gray-700 text-xs">{{ strtoupper($withdrawal->bank) }} - {{ $withdrawal->nomor_rekening }}</p>
              <p class="text-gray-400 text-xs">{{ $withdrawal->nama_rekening }}</p>
            </td>
            <td class="px-4 py-3">
              <p class="text-xs font-semibold px-2 py-1 rounded-full
                {{ $withdrawal->status === 'processed' ? 'bg-green-100 text-green-700' :
                   ($withdrawal->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-600') }}">
                {{ $withdrawal->status === 'processed' ? 'Diproses' : ($withdrawal->status === 'pending' ? 'Pending' : 'Ditolak') }}
              </p>
              @if($withdrawal->processed_by)
                <p class="text-gray-400 text-[11px] mt-1">oleh {{ $withdrawal->processedBy->nama_lengkap ?? 'Admin' }}</p>
              @endif
            </td>
            <td class="px-4 py-3">
              @if($withdrawal->status === 'pending')
              <div class="space-y-2">
                <form method="POST" action="{{ route('admin.penarikan.approve', $withdrawal) }}">
                  @csrf
                  <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white text-xs font-bold px-3 py-2 rounded-xl transition">
                    Setujui
                  </button>
                </form>
                <button type="button" onclick="openRejectModal({{ $withdrawal->id }}, '{{ addslashes($withdrawal->pengelola->nama_lengkap ?? '') }}')"
                        class="w-full bg-red-500 hover:bg-red-600 text-white text-xs font-bold px-3 py-2 rounded-xl transition">
                  Tolak
                </button>
              </div>
              @else
                <p class="text-xs text-gray-500">-</p>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="px-4 py-12 text-center text-gray-400">Belum ada penarikan.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-4 py-4 border-t border-gray-100">{{ $withdrawals->withQueryString()->links() }}</div>
  </div>
</div>

<div id="reject-modal" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl p-6">
    <h3 class="font-bold text-navy-deep text-lg mb-2">Tolak Penarikan</h3>
    <p id="reject-withdrawal-name" class="text-sm text-gray-500 mb-4"></p>
    <form id="reject-withdrawal-form" method="POST">
      @csrf
      <textarea name="catatan_admin" placeholder="Catatan penolakan (opsional)"
        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-navy-mid resize-none"
        rows="4"></textarea>
      <div class="mt-4 flex gap-3">
        <button type="button" onclick="closeRejectModal()"
          class="flex-1 bg-gray-100 text-gray-700 font-bold py-3 rounded-xl hover:bg-gray-200 transition text-sm">
          Batal
        </button>
        <button type="submit"
          class="flex-1 bg-red-500 text-white font-bold py-3 rounded-xl hover:bg-red-600 transition text-sm">
          Tolak Penarikan
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function openRejectModal(id, name) {
    document.getElementById('reject-withdrawal-name').textContent = 'Tolak penarikan dari ' + (name || 'EO ini') + '?';
    document.getElementById('reject-withdrawal-form').action = '/admin/penarikan/' + id + '/reject';
    document.getElementById('reject-modal').classList.remove('hidden');
  }
  function closeRejectModal() {
    document.getElementById('reject-modal').classList.add('hidden');
  }
</script>
@endpush
