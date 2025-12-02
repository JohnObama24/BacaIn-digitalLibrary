@extends('layouts.admin')

@section('title', 'Laporan Digital')
@section('header', 'Laporan Digital')
@section('subheader', 'Export laporan peminjaman ke PDF dan Excel')

@section('content')
  <div class="space-y-6">
    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-md p-6">
      <form action="{{ route('laporan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
          <input type="date" name="start_date" value="{{ $startDate }}"
            class="w-full rounded-lg border-gray-300 focus:border-primary-blue focus:ring focus:ring-primary-blue/20">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
          <input type="date" name="end_date" value="{{ $endDate }}"
            class="w-full rounded-lg border-gray-300 focus:border-primary-blue focus:ring focus:ring-primary-blue/20">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
          <select name="status"
            class="w-full rounded-lg border-gray-300 focus:border-primary-blue focus:ring focus:ring-primary-blue/20">
            <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="dipinjam" {{ $status == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
            <option value="dikembalikan" {{ $status == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
            <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
          </select>
        </div>
        <div class="flex space-x-2">
          <button type="submit"
            class="bg-primary-blue text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex-1">
            Filter
          </button>
          <a href="{{ route('laporan.index') }}"
            class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-200 transition">
            Reset
          </a>
        </div>
      </form>
    </div>

    <!-- Export Buttons -->
    <div class="flex space-x-3">
      <form action="{{ route('laporan.pdf') }}" method="GET" target="_blank" class="">
        <input type="hidden" name="start_date" value="{{ $startDate }}">
        <input type="hidden" name="end_date" value="{{ $endDate }}">
        <input type="hidden" name="status" value="{{ $status }}">
        <button type="submit"
          class="flex items-center bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition shadow-sm">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>
          Export PDF
        </button>
      </form>

      <form action="{{ route('laporan.excel') }}" method="GET" target="_blank">
        <input type="hidden" name="start_date" value="{{ $startDate }}">
        <input type="hidden" name="end_date" value="{{ $endDate }}">
        <input type="hidden" name="status" value="{{ $status }}">
        <button type="submit"
          class="flex items-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition shadow-sm">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          Export Excel
        </button>
      </form>
    </div>

    <!-- Preview Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buku</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Denda</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($peminjaman as $item)
              <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ $item->tanggal_peminjaman->format('d/m/Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ $item->user->name }}</div>
                  <div class="text-sm text-gray-500">{{ $item->user->email }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ $item->buku->judul }}</div>
                  <div class="text-sm text-gray-500">{{ $item->isEbook() ? 'E-Book' : 'Fisik' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $item->status_peminjaman === 'dipinjam' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $item->status_peminjaman === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $item->status_peminjaman === 'dikembalikan' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $item->status_peminjaman === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $item->status_peminjaman === 'selesai' ? 'bg-gray-100 text-gray-800' : '' }}">
                    {{ ucfirst($item->status_peminjaman) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  @if($item->denda > 0)
                    <span class="text-red-600 font-medium">Rp {{ number_format($item->denda, 0, ',', '.') }}</span>
                    <span class="text-xs block {{ $item->denda_lunas ? 'text-green-600' : 'text-red-500' }}">
                      {{ $item->denda_lunas ? '(Lunas)' : '(Belum Lunas)' }}
                    </span>
                  @else
                    -
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                  Tidak ada data untuk periode ini.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection