@extends('layouts.app')

@section('content')
<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pengurusan Kelas & Guru Kelas</h2>
            <p class="text-sm text-gray-600">Mencipta bilik darjah dan menugaskan guru kelas mengikut sesi tahun akademik.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah Kelas -->
        <div class="bg-white p-6 rounded-lg shadow border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Kelas Baharu</h3>
            <form action="{{ route('master.kelas.store') }}" method="POST" class="space-y-4">
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
                    <label class="block text-sm font-medium text-gray-700">Sesi Akademik Aktif</label>
                    <select name="tahun_akademik_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @foreach($tahunAkademikList as $ta)
                            <option value="{{ $ta->id }}">{{ $ta->nama_tahun }} ({{ $ta->sekolah->nama_sekolah ?? '' }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Kelas (Contoh: 5 Cemerlang)</label>
                    <input type="text" name="nama_kelas" value="{{ old('nama_kelas') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @error('nama_kelas') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tingkatan / Darjah (1 - 6)</label>
                    <input type="number" name="tingkatan_darjah" min="1" max="6" value="{{ old('tingkatan_darjah', 1) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md text-sm font-semibold hover:bg-indigo-700">Simpan Kelas</button>
            </form>
        </div>

        <!-- Senarai Kelas -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Senarai Kelas Berdaftar</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Tingkatan & Nama Kelas</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Guru Kelas Semasa</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Sesi Akademik</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-500">Penugasan Guru</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($kelasList as $kelas)
                            @php
                                $guruUtama = $kelas->kelasGuru->where('peranan', 'GURU_UTAMA')->whereNull('tarikh_tamat')->first();
                            @endphp
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-gray-900">{{ $kelas->nama_kelas }}</div>
                                    <div class="text-xs text-gray-500">Tingkatan / Darjah: {{ $kelas->tingkatan_darjah }}</div>
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    @if($guruUtama)
                                        <div class="font-semibold text-indigo-700">🧑‍🏫 {{ $guruUtama->pengguna->nama }}</div>
                                    @else
                                        <span class="text-gray-400 italic">Belum ditugaskan</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600">{{ $kelas->tahunAkademik->nama_tahun ?? '-' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div x-data="{ openModal: false }">
                                        <button @click="openModal = true" class="text-xs bg-indigo-50 text-indigo-600 font-semibold px-3 py-1.5 rounded hover:bg-indigo-100">Tugaskan Guru</button>

                                        <!-- Modal Tugaskan Guru -->
                                        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-500 bg-opacity-75 flex items-center justify-center p-4 text-left" style="display: none;">
                                            <div class="bg-white rounded-lg p-6 max-w-md w-full shadow-xl">
                                                <h4 class="text-lg font-bold text-gray-900 mb-4">Penugasan Guru Kelas: {{ $kelas->nama_kelas }}</h4>
                                                <form action="{{ route('master.kelas-guru.store') }}" method="POST" class="space-y-4">
                                                    @csrf
                                                    <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
                                                    <input type="hidden" name="tahun_akademik_id" value="{{ $kelas->tahun_akademik_id }}">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700">Pilih Guru</label>
                                                        <select name="pengguna_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                            @foreach($guruList as $g)
                                                                <option value="{{ $g->id }}">{{ $g->nama }} ({{ $g->jawatan ?? 'Guru' }})</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700">Peranan</label>
                                                        <select name="peranan" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                            <option value="GURU_UTAMA">Guru Utama / Guru Kelas</option>
                                                            <option value="GURU_PENDAMPING">Guru Pendamping</option>
                                                        </select>
                                                    </div>
                                                    <div class="flex justify-end space-x-2 pt-2">
                                                        <button type="button" @click="openModal = false" class="px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-semibold rounded">Batal</button>
                                                        <button type="submit" class="px-3 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded">Simpan Penugasan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-500">Tiada rekod kelas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100">
                {{ $kelasList->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
