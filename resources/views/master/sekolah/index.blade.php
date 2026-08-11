@extends('layouts.app')

@section('content')
<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pengurusan Sekolah</h2>
            <p class="text-sm text-gray-600">Senarai profil sekolah dan maklumat perhubungan institusi.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah Sekolah -->
        <div class="bg-white p-6 rounded-lg shadow border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Sekolah Baharu</h3>
            <form action="{{ route('master.sekolah.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kod Sekolah</label>
                    <input type="text" name="kod_sekolah" value="{{ old('kod_sekolah') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @error('kod_sekolah') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Sekolah</label>
                    <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @error('nama_sekolah') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kod PPD</label>
                        <input type="text" name="kod_ppd" value="{{ old('kod_ppd') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kod JPN</label>
                        <input type="text" name="kod_jpn" value="{{ old('kod_jpn') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Sekolah</label>
                    <select name="jenis_sekolah" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="RENDAH">Rendah (SK/SJK)</option>
                        <option value="MENENGAH">Menengah (SMK/SMJK)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">No. Telefon</label>
                    <input type="text" name="telefon" value="{{ old('telefon') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Emel Rasmi</label>
                    <input type="email" name="emel" value="{{ old('emel') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="alamat" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('alamat') }}</textarea>
                </div>
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md text-sm font-semibold hover:bg-indigo-700">Simpan Sekolah</button>
            </form>
        </div>

        <!-- Senarai Sekolah -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Senarai Sekolah Berdaftar</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Kod / Nama Sekolah</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Jenis</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Perhubungan</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-500">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($sekolahList as $sekolah)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-gray-900">{{ $sekolah->nama_sekolah }}</div>
                                    <div class="text-xs text-indigo-600 font-mono">{{ $sekolah->kod_sekolah }} | PPD: {{ $sekolah->kod_ppd ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full font-semibold bg-blue-100 text-blue-800">
                                        {{ $sekolah->jenis_sekolah->value ?? $sekolah->jenis_sekolah }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600">
                                    <div>📞 {{ $sekolah->telefon ?? '-' }}</div>
                                    <div>✉️ {{ $sekolah->emel ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    @can('sekolah.urus')
                                    <form action="{{ route('master.sekolah.destroy', $sekolah) }}" method="POST" class="inline" onsubmit="return confirm('Padam sekolah ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-semibold">Padam</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-500">Tiada rekod sekolah dijumpai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100">
                {{ $sekolahList->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
