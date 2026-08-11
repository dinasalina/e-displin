@extends('layouts.app')

@section('content')
<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pengurusan Penjaga Murid</h2>
            <p class="text-sm text-gray-600">Daftar maklumat ibubapa/penjaga dan ikatan perhubungan murid.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah Penjaga -->
        <div class="bg-white p-6 rounded-lg shadow border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Penjaga Baharu</h3>
            <form action="{{ route('master.penjaga.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sekolah</label>
                    <select name="sekolah_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @foreach($sekolahList as $s)
                            <option value="{{ $s->id }}">{{ $s->nama_sekolah }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Penuh Penjaga</label>
                    <input type="text" name="nama_penuh" value="{{ old('nama_penuh') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @error('nama_penuh') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">No. Kad Pengenalan</label>
                    <input type="text" name="no_kp" value="{{ old('no_kp') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">No. Telefon Bimbit</label>
                    <input type="text" name="no_telefon" value="{{ old('no_telefon') }}" required placeholder="012-3456789" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Alamat Emel</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Hubungkait (Bapa/Ibu/Penjaga Utama)</label>
                    <input type="text" name="hubungkait" value="{{ old('hubungkait', 'Bapa') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Hubungkan Kepada Murid</label>
                    <select name="murid_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">-- Pilih Murid (Pilihan) --</option>
                        @foreach($muridList as $m)
                            <option value="{{ $m->id }}">{{ $m->nama_penuh }} (KP: {{ $m->no_kp }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md text-sm font-semibold hover:bg-indigo-700">Simpan Penjaga</button>
            </form>
        </div>

        <!-- Senarai Penjaga -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Senarai Penjaga Berdaftar</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Nama / No. KP</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Perhubungan</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Anak / Murid Terikat</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-500">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($penjagaList as $penjaga)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-gray-900">{{ $penjaga->nama_penuh }}</div>
                                    <div class="text-xs text-gray-500 font-mono">KP: {{ $penjaga->no_kp }}</div>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600">
                                    <div class="font-semibold text-gray-800">📞 {{ $penjaga->no_telefon }}</div>
                                    <div>Hubungan: {{ $penjaga->hubungkait }}</div>
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    @forelse($penjaga->murid as $m)
                                        <span class="inline-block bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded font-semibold text-xs mb-1">
                                            👧 {{ $m->nama_penuh }}
                                        </span>
                                    @empty
                                        <span class="text-gray-400 italic">Belum terikat murid</span>
                                    @endforelse
                                </td>
                                <td class="px-4 py-3 text-right">
                                    @can('penjaga.urus')
                                    <form action="{{ route('master.penjaga.destroy', $penjaga) }}" method="POST" class="inline" onsubmit="return confirm('Padam penjaga ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-semibold">Padam</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-500">Tiada rekod penjaga.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100">
                {{ $penjagaList->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
