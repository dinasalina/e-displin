@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <span>Modul Disiplin</span>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-rose-600 dark:text-rose-400 font-semibold">Laporan Kes Baharu</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold font-heading text-slate-900 dark:text-white tracking-tight">Borang Laporan Kes Salah Laku</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Laporkan insiden salah laku murid secara rasmi bagi tindakan pengurusan sekolah.</p>
        </div>

        <a href="{{ route('disiplin.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
            ⬅️ Kembali ke Senarai
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 rounded-2xl text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 p-6 sm:p-8 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 shadow-sm">
        <form action="{{ route('disiplin.lapor.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">Sekolah</label>
                    <select name="sekolah_id" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                        @foreach($sekolahList as $s)
                            <option value="{{ $s->id }}" {{ old('sekolah_id') == $s->id ? 'selected' : '' }}>{{ $s->nama_sekolah }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">Murid Involved</label>
                    <select name="murid_id" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="">-- Pilih Murid --</option>
                        @foreach($muridList as $m)
                            <option value="{{ $m->id }}" {{ old('murid_id') == $m->id ? 'selected' : '' }}>
                                {{ $m->nama_penuh }} (KP: {{ $m->no_kp }}) - {{ $m->sekolah->nama_sekolah ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('murid_id') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">Kategori Salah Laku</label>
                    <select name="kategori_disiplin_id" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoriList as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_disiplin_id') == $k->id ? 'selected' : '' }}>
                                [{{ $k->kod_kategori }}] {{ $k->nama_kategori }} (Default: {{ $k->tahap_default->value ?? $k->tahap_default }})
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_disiplin_id') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">Tahap Kes (Pilihan override)</label>
                    <select name="tahap_kes" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="">-- Ikut Kategori Default --</option>
                        @foreach($tahapOptions as $tahap)
                            <option value="{{ $tahap->value }}" {{ old('tahap_kes') == $tahap->value ? 'selected' : '' }}>{{ $tahap->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">Tarikh & Waktu Kejadian</label>
                    <input type="datetime-local" name="tarikh_kejadian" value="{{ old('tarikh_kejadian', now()->format('Y-m-d\TH:i')) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                    @error('tarikh_kejadian') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">Lokasi Kejadian</label>
                    <input type="text" name="lokasi_kejadian" value="{{ old('lokasi_kejadian') }}" placeholder="e.g. Kantin Sekolah / Kelas 5 Cemerlang" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">Keterangan / Kronologi Kes</label>
                <textarea name="keterangan_kes" rows="4" required placeholder="Nyatakan kronologi penuh insiden salah laku yang berlaku..." class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('keterangan_kes') }}</textarea>
                @error('keterangan_kes') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">Muat Naik Lampiran Bukti (Gambar / PDF)</label>
                <input type="file" name="lampiran[]" multiple accept="image/*,.pdf" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-slate-700 dark:file:text-slate-200 cursor-pointer">
                <p class="mt-1 text-[11px] text-slate-400">Boleh pilih lebih daripada satu fail (Format: JPG, PNG, PDF. Saiz max 5MB/fail).</p>
            </div>

            <div class="flex justify-end space-x-3 pt-6 border-t border-slate-100 dark:border-slate-700">
                <a href="{{ route('disiplin.index') }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold hover:bg-slate-200 transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-extrabold shadow-md shadow-rose-600/20 transition-all hover:scale-[1.02]">Hantar Laporan Kes</button>
            </div>
        </form>
    </div>
</div>
@endsection
