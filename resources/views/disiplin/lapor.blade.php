@extends('layouts.app')

@section('content')
<div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-red-700">Borang Laporan Kes Salah Laku Disiplin</h2>
        <p class="text-sm text-gray-600">Laporkan insiden salah laku murid untuk tindakan pihak pengurusan sekolah.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded-lg shadow border border-gray-100">
        <form action="{{ route('disiplin.lapor.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sekolah</label>
                    <select name="sekolah_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @foreach($sekolahList as $s)
                            <option value="{{ $s->id }}" {{ old('sekolah_id') == $s->id ? 'selected' : '' }}>{{ $s->nama_sekolah }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Murid Involved</label>
                    <select name="murid_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">-- Pilih Murid --</option>
                        @foreach($muridList as $m)
                            <option value="{{ $m->id }}" {{ old('murid_id') == $m->id ? 'selected' : '' }}>
                                {{ $m->nama_penuh }} (KP: {{ $m->no_kp }}) - {{ $m->sekolah->nama_sekolah ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('murid_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Kategori Salah Laku</label>
                    <select name="kategori_disiplin_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoriList as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_disiplin_id') == $k->id ? 'selected' : '' }}>
                                [{{ $k->kod_kategori }}] {{ $k->nama_kategori }} (Default: {{ $k->tahap_default->value ?? $k->tahap_default }})
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_disiplin_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tahap Kes (Pilihan override)</label>
                    <select name="tahap_kes" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">-- Ikut Kategori Default --</option>
                        @foreach($tahapOptions as $tahap)
                            <option value="{{ $tahap->value }}" {{ old('tahap_kes') == $tahap->value ? 'selected' : '' }}>{{ $tahap->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tarikh & Waktu Kejadian</label>
                    <input type="datetime-local" name="tarikh_kejadian" value="{{ old('tarikh_kejadian', now()->format('Y-m-d\TH:i')) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @error('tarikh_kejadian') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Lokasi Kejadian</label>
                    <input type="text" name="lokasi_kejadian" value="{{ old('lokasi_kejadian') }}" placeholder="e.g. Kantin Sekolah / Kelas 5 Cemerlang" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Keterangan / Kronologi Kes</label>
                <textarea name="keterangan_kes" rows="4" required placeholder="Nyatakan kronologi penuh insiden salah laku yang berlaku..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('keterangan_kes') }}</textarea>
                @error('keterangan_kes') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Muat Naik Lampiran Bukti (Gambar / Dokumen PDF)</label>
                <input type="file" name="lampiran[]" multiple accept="image/*,.pdf" class="mt-1 block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="mt-1 text-xs text-gray-500">Boleh pilih lebih daripada satu fail (Format: JPG, PNG, PDF. Saiz max 5MB/fail).</p>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('disiplin.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-200">Batal</a>
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-md text-sm font-bold hover:bg-red-700">Hantar Laporan Kes</button>
            </div>
        </form>
    </div>
</div>
@endsection
