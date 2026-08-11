@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ showVoidModal: false, showTindakanModal: false, showEskalasiModal: false }">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-extrabold font-mono text-indigo-600 dark:text-indigo-400 tracking-tight">{{ $disiplin->no_kes }}</h1>
                @if($disiplin->is_void)
                    <span class="px-3 py-1 bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300 rounded-full text-xs font-extrabold">VOID (BATAL)</span>
                @else
                    @php
                        $sVal = $disiplin->status_kes->value ?? $disiplin->status_kes;
                    @endphp
                    <span class="px-3 py-1 bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300 rounded-full text-xs font-extrabold">{{ $sVal }}</span>
                @endif
            </div>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">Dilaporkan pada {{ \Carbon\Carbon::parse($disiplin->created_at)->format('d/m/Y h:i A') }} oleh <strong class="text-slate-700 dark:text-slate-200">{{ $disiplin->pelapor->nama ?? '-' }}</strong></p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('disiplin.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl hover:bg-slate-200 transition">
                ⬅️ Kembali ke Senarai
            </a>

            @if(!$disiplin->is_void)
                @can('disiplin.semak')
                <button @click="showTindakanModal = true" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-extrabold rounded-xl shadow-md shadow-indigo-600/20 transition">
                    + Kemaskini Status / Tindakan
                </button>
                @endcan

                @can('disiplin.semak')
                @if(($disiplin->tahap_kes->value ?? $disiplin->tahap_kes) === 'BERAT' && ($disiplin->status_kes->value ?? $disiplin->status_kes) !== 'DITUTUP')
                <button @click="showEskalasiModal = true" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-extrabold rounded-xl shadow-md shadow-amber-600/20 transition">
                    ⚡ Eskalasi Ke PK HEM
                </button>
                @endif
                @endcan

                @can('disiplin.void')
                <button @click="showVoidModal = true" class="px-4 py-2 bg-rose-50 dark:bg-rose-950/80 text-rose-700 dark:text-rose-300 text-xs font-extrabold rounded-xl border border-rose-200 dark:border-rose-800 hover:bg-rose-100 transition">
                    🚫 Batal Kes (Void)
                </button>
                @endcan
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 rounded-2xl text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Maklumat Kes & Murid -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-800 p-6 sm:p-8 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 shadow-sm space-y-6">
                <h3 class="text-base font-extrabold font-heading text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-3">Butiran Kes Salah Laku</h3>

                <div class="grid grid-cols-2 gap-4 text-xs sm:text-sm">
                    <div class="p-3 bg-slate-50 dark:bg-slate-700/40 rounded-xl border border-slate-100 dark:border-slate-700/50">
                        <span class="text-slate-400 block text-[11px] font-bold uppercase tracking-wider">Murid Involved:</span>
                        <span class="font-extrabold text-slate-900 dark:text-white text-base block mt-0.5">{{ $disiplin->murid->nama_penuh ?? '-' }}</span>
                        <span class="text-xs text-slate-500 block">KP: {{ $disiplin->murid->no_kp ?? '-' }}</span>
                    </div>

                    <div class="p-3 bg-slate-50 dark:bg-slate-700/40 rounded-xl border border-slate-100 dark:border-slate-700/50">
                        <span class="text-slate-400 block text-[11px] font-bold uppercase tracking-wider">Kategori Salah Laku:</span>
                        <span class="font-extrabold text-indigo-600 dark:text-indigo-400 text-base block mt-0.5">{{ $disiplin->kategoriDisiplin->nama_kategori ?? '-' }}</span>
                        <span class="text-xs text-slate-500 block">Kod: {{ $disiplin->kategoriDisiplin->kod_kategori ?? '-' }}</span>
                    </div>

                    <div class="p-3 bg-slate-50 dark:bg-slate-700/40 rounded-xl border border-slate-100 dark:border-slate-700/50">
                        <span class="text-slate-400 block text-[11px] font-bold uppercase tracking-wider">Tahap Kes:</span>
                        <span class="font-extrabold text-rose-600 dark:text-rose-400 text-base block mt-0.5">{{ $disiplin->tahap_kes->value ?? $disiplin->tahap_kes }}</span>
                    </div>

                    <div class="p-3 bg-slate-50 dark:bg-slate-700/40 rounded-xl border border-slate-100 dark:border-slate-700/50">
                        <span class="text-slate-400 block text-[11px] font-bold uppercase tracking-wider">Lokasi Kejadian:</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-200 text-sm block mt-0.5">{{ $disiplin->lokasi_kejadian ?? '-' }}</span>
                    </div>
                </div>

                <div class="pt-2">
                    <span class="text-slate-400 block text-[11px] font-bold uppercase tracking-wider mb-2">Kronologi / Keterangan Kes:</span>
                    <p class="text-sm text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-slate-700/40 p-4 rounded-xl border border-slate-200/60 dark:border-slate-700/60 whitespace-pre-line leading-relaxed">{{ $disiplin->keterangan_kes }}</p>
                </div>

                @if($disiplin->lampiran->isNotEmpty())
                <div class="pt-2 border-t border-slate-100 dark:border-slate-700">
                    <span class="text-slate-400 block text-[11px] font-bold uppercase tracking-wider mb-3">Lampiran Bukti:</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($disiplin->lampiran as $file)
                            <a href="{{ Storage::url($file->laluan_fail) }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2 bg-indigo-50 dark:bg-indigo-950/80 hover:bg-indigo-100 text-indigo-700 dark:text-indigo-300 text-xs font-bold rounded-xl border border-indigo-200 dark:border-indigo-800 transition">
                                <span>📄 {{ $file->nama_fail_asal }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Rekod Tindakan Disiplin yang Dikenakan -->
            <div class="bg-white dark:bg-slate-800 p-6 sm:p-8 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 shadow-sm space-y-4">
                <h3 class="text-base font-extrabold font-heading text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-3">Tindakan Disiplin Dikenakan</h3>
                @forelse($disiplin->tindakan as $t)
                    <div class="p-4 bg-indigo-50/60 dark:bg-indigo-950/40 rounded-xl border border-indigo-100 dark:border-indigo-900/60">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-extrabold text-indigo-900 dark:text-indigo-300 text-sm">{{ $t->jenis_tindakan }}</span>
                            <span class="text-[11px] text-slate-400">Oleh: {{ $t->diberiOleh->nama ?? '-' }}</span>
                        </div>
                        <p class="text-xs text-slate-700 dark:text-slate-300">{{ $t->keterangan }}</p>
                        @if($t->tarikh_mula)
                            <div class="text-[11px] text-slate-400 mt-2">Tempoh: {{ $t->tarikh_mula }} hingga {{ $t->tarikh_tamat ?? 'Selesai' }}</div>
                        @endif
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic">Belum ada tindakan disiplin direkodkan.</p>
                @endforelse
            </div>
        </div>

        <!-- Sejarah Status & Audit Trail Timeline -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 shadow-sm">
                <h3 class="text-base font-extrabold font-heading text-slate-900 dark:text-white mb-4 border-b border-slate-100 dark:border-slate-700 pb-3">Sejarah Status & Audit Trail</h3>
                <div class="space-y-6 relative before:absolute before:inset-0 before:left-3.5 before:w-0.5 before:bg-slate-200 dark:before:bg-slate-700">
                    @foreach($disiplin->sejarahStatus as $s)
                        <div class="relative pl-8">
                            <div class="absolute left-2 top-1 w-3.5 h-3.5 rounded-full bg-indigo-600 dark:bg-indigo-400 ring-4 ring-white dark:ring-slate-800"></div>
                            <div class="text-[10px] font-semibold text-slate-400">{{ \Carbon\Carbon::parse($s->created_at)->format('d/m/Y h:i A') }}</div>
                            <div class="font-extrabold text-xs text-slate-800 dark:text-slate-200 mt-0.5">{{ $s->status_baharu->value ?? $s->status_baharu }}</div>
                            <div class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ $s->catatan }}</div>
                            <div class="text-[10px] text-slate-400 font-semibold mt-1">Oleh: {{ $s->dikemaskiniOleh->nama ?? '-' }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Status / Tindakan -->
    <div x-show="showTindakanModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
        <div class="bg-white dark:bg-slate-800 rounded-2xl max-w-md w-full p-6 space-y-4 border border-slate-200 dark:border-slate-700 shadow-2xl">
            <h3 class="text-base font-extrabold font-heading text-slate-900 dark:text-white">Kemaskini Status & Tindakan</h3>
            <form action="{{ route('disiplin.tindakan.update', $disiplin) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1">Status Baharu</label>
                    <select name="status_kes" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-200 outline-none">
                        <option value="DALAM_SEMAKAN">DALAM_SEMAKAN</option>
                        <option value="DALAM_TINDAKAN">DALAM_TINDAKAN</option>
                        <option value="DITUTUP">DITUTUP</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1">Jenis Tindakan Dikenakan</label>
                    <input type="text" name="jenis_tindakan" required placeholder="Contoh: Amaran Bertulis / Gantung Sekolah 3 Hari" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-200 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1">Keterangan Tindakan</label>
                    <textarea name="keterangan_tindakan" rows="2" required placeholder="Butiran hukuman / kaunselling..." class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-200 outline-none"></textarea>
                </div>
                <div class="flex justify-end space-x-2 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <button type="button" @click="showTindakanModal = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-xl text-xs shadow-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Eskalasi KE PK HEM -->
    <div x-show="showEskalasiModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
        <div class="bg-white dark:bg-slate-800 rounded-2xl max-w-md w-full p-6 space-y-4 border border-slate-200 dark:border-slate-700 shadow-2xl">
            <h3 class="text-base font-extrabold font-heading text-amber-600 dark:text-amber-400">Eskalasi Kes Berat ke PK HEM</h3>
            <p class="text-xs text-slate-500">Kes berat memerlukan pengesahan berperingkat PK HEM & Pengetua sebelum tindakan punitif berat dilaksanakan.</p>
            <form action="{{ route('disiplin.eskalasi.pkhem', $disiplin) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="keputusan" value="HANTAR_PKHEM">
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1">Syor & Catatan Guru Disiplin</label>
                    <textarea name="catatan" rows="3" required placeholder="Ulasan serta syor tindakan kes berat kepada PK HEM..." class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-200 outline-none"></textarea>
                </div>
                <div class="flex justify-end space-x-2 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <button type="button" @click="showEskalasiModal = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white font-extrabold rounded-xl text-xs shadow-md">Hantar Eskalasi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Void -->
    <div x-show="showVoidModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
        <div class="bg-white dark:bg-slate-800 rounded-2xl max-w-md w-full p-6 space-y-4 border border-slate-200 dark:border-slate-700 shadow-2xl">
            <h3 class="text-base font-extrabold font-heading text-rose-600 dark:text-rose-400">Pembatalan Kes (Void System)</h3>
            <p class="text-xs text-slate-500">Pembatalan kes adalah kekal untuk membatalkan laporan tersilap. Rekod audit akan disimpan.</p>
            <form action="{{ route('disiplin.void', $disiplin) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1">Sebab Pembatalan (Rasmi)</label>
                    <textarea name="void_reason" rows="3" required placeholder="Nyatakan sebab pembatalan kes secara rasmi..." class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-200 outline-none"></textarea>
                </div>
                <div class="flex justify-end space-x-2 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <button type="button" @click="showVoidModal = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-extrabold rounded-xl text-xs shadow-md">Sahkan Pembatalan (Void)</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
