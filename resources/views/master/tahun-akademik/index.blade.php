@extends('layouts.app')

@section('content')
<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pengurusan Sesi Tahun Akademik</h2>
            <p class="text-sm text-gray-600">Kawalan sesi persekolahan dan penandaan sesi aktif semasa.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah Sesi Akademik -->
        <div class="bg-white p-6 rounded-lg shadow border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Sesi Akademik</h3>
            <form action="{{ route('master.tahun-akademik.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sekolah</label>
                    <select name="sekolah_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @foreach($sekolahList as $s)
                            <option value="{{ $s->id }}">{{ $s->nama_sekolah }} ({{ $s->kod_sekolah }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Sesi (Contoh: 2025/2026)</label>
                    <input type="text" name="nama_tahun" value="{{ old('nama_tahun') }}" required placeholder="2025/2026" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @error('nama_tahun') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tarikh Mula Sesi</label>
                    <input type="date" name="tarikh_mula" value="{{ old('tarikh_mula') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tarikh Tamat Sesi</label>
                    <input type="date" name="tarikh_tamat" value="{{ old('tarikh_tamat') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_aktif" value="1" id="is_aktif" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <label for="is_aktif" class="ms-2 text-sm font-medium text-gray-700">Set sebagai Sesi Aktif Semasa</label>
                </div>
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md text-sm font-semibold hover:bg-indigo-700">Simpan Sesi Akademik</button>
            </form>
        </div>

        <!-- Senarai Sesi -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Senarai Sesi Akademik</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Sekolah</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Nama Sesi</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Tempoh Tarikh</th>
                            <th class="px-4 py-3 text-center font-medium text-gray-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($tahunList as $tahun)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-gray-900">{{ $tahun->sekolah->nama_sekolah ?? '-' }}</td>
                                <td class="px-4 py-3 font-bold text-indigo-600">{{ $tahun->nama_tahun }}</td>
                                <td class="px-4 py-3 text-xs text-gray-600">
                                    {{ \Carbon\Carbon::parse($tahun->tarikh_mula)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tahun->tarikh_tamat)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($tahun->is_aktif)
                                        <span class="px-2.5 py-1 text-xs rounded-full font-bold bg-green-100 text-green-800">AKTIF</span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs rounded-full font-semibold bg-gray-100 text-gray-600">TIDAK AKTIF</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-500">Tiada rekod sesi akademik.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100">
                {{ $tahunList->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
