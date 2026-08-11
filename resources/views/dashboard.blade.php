@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ 
    aiModalOpen: false, 
    aiLoading: false, 
    aiResult: null,
    generateAiInsight() {
        this.aiModalOpen = true;
        this.aiLoading = true;
        this.aiResult = null;
        
        fetch('{{ route('ai.generate') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ context: 'Analisis trend disiplin sekolah secara keseluruhan.' })
        })
        .then(res => res.json())
        .then(data => {
            this.aiLoading = false;
            this.aiResult = data.data;
        })
        .catch(err => {
            this.aiLoading = false;
            alert('Ralat jana AI.');
        });
    }
}">
    <!-- PAGE HEADER & QUICK ACTIONS -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <span>Sistem e-Disiplin</span>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-indigo-600 dark:text-indigo-400 font-semibold">Dashboard Utama</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold font-heading text-slate-900 dark:text-white tracking-tight">Portal Pengurusan Disiplin Sekolah</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Selamat kembali, {{ Auth::user()->name }}. Ringkasan kes disiplin dan analitik berprediktif AI bagi {{ Auth::user()->sekolah->nama_sekolah ?? 'Semua Sekolah' }}.</p>
        </div>

        @can('disiplin.lapor')
        <div class="flex items-center gap-3">
            <a href="{{ route('disiplin.lapor.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs sm:text-sm font-bold shadow-md shadow-indigo-600/20 transition-all hover:scale-[1.02]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ Laporkan Kes Baharu</span>
            </a>
        </div>
        @endcan
    </div>

    <!-- 4 KAD STATISTIK UTAMA (DINAMIK DARI DATABASE) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        
        <!-- 1. Jumlah Murid -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 shadow-sm relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Jumlah Murid</span>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ number_format($jumlahMurid) }}</span>
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950 px-2 py-0.5 rounded-full">Aktif</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Status Murid Berdaftar</p>
        </div>

        <!-- 2. Kes Hari Ini -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 shadow-sm relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kes Hari Ini</span>
                <div class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-950 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $kesHariIni }} Kes</span>
                <span class="text-xs font-bold text-sky-600 dark:text-sky-400 bg-sky-50 dark:bg-sky-950 px-2 py-0.5 rounded-full">{{ now()->format('d M Y') }}</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Laporan Kes Baharu Diterima</p>
        </div>

        <!-- 3. Kes Belum Disemak -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 shadow-sm relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kes Belum Disemak</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-amber-600 dark:text-amber-400">{{ $kesBelumDisemak }} Kes</span>
                <span class="text-xs font-bold text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950 px-2 py-0.5 rounded-full">Menunggu Tindakan</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Status: DILAPORKAN</p>
        </div>

        <!-- 4. Kes Berat -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 shadow-sm relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kes Berat Aktif</span>
                <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-rose-600 dark:text-rose-400">{{ $kesBerat }} Kes</span>
                <span class="text-xs font-bold text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-950 px-2 py-0.5 rounded-full">Sequential Approval</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Perlu Kelulusan PK HEM & Pengetua</p>
        </div>

    </div>

    <!-- WIDGET AI: "AI INSIGHT" -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-6 text-white shadow-xl border border-indigo-500/30">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>
        
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 relative z-10">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-300 text-xs font-bold">
                    <svg class="w-4 h-4 text-indigo-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>e-Disiplin AI Predictive Insight (Model: {{ config('ai.default_model') }})</span>
                </div>
                <h2 class="text-lg sm:text-xl font-bold font-heading">Analisis Gelagat & Ramalan Disiplin Murid</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-2 text-xs">
                    <div class="p-3 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
                        <span class="text-indigo-300 font-bold block mb-1">📈 Corak Kelewatan</span>
                        <p class="text-slate-300 text-[11px]">Kelewatan murid meningkat 18% pada Isnin pagi. Cadangan: Program Sapaan Mesra di pintu pagar utama.</p>
                    </div>
                    <div class="p-3 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
                        <span class="text-amber-300 font-bold block mb-1">⚠️ Ambang Demerit</span>
                        <p class="text-slate-300 text-[11px]">3 murid melepasi 50 mata. Penjana Surat Amaran 2 sedia dicetak.</p>
                    </div>
                    <div class="p-3 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
                        <span class="text-emerald-300 font-bold block mb-1">💡 Impak Kaunseling</span>
                        <p class="text-slate-300 text-[11px]">Sesi kaunseling mengurangkan kes salah laku berulang sebanyak 34% bulan ini.</p>
                    </div>
                </div>
            </div>

            <div class="shrink-0 flex flex-col gap-2">
                <button @click="generateAiInsight()" class="w-full sm:w-auto px-5 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 transition-all text-center">
                    🤖 Jana Pelan Tindakan AI
                </button>
                <span class="text-[10px] text-slate-400 text-center">Audit Log berdaftar di ai_prompt_history</span>
            </div>
        </div>
    </div>

    <!-- SENARAI KES TERKINI (DINAMIK) -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-700/60 flex justify-between items-center">
            <h3 class="font-extrabold text-slate-900 dark:text-white text-sm sm:text-base">Rekod Kes Disiplin Terkini (Pangkalan Data)</h3>
            <a href="{{ route('disiplin.index') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Semua ➔</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="p-4">No Kes & Tarikh</th>
                        <th class="p-4">Murid Involved</th>
                        <th class="p-4">Kategori & Tahap</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-xs sm:text-sm">
                    @forelse($senaraiKesTerkini as $rekod)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="p-4">
                                <div class="font-bold font-mono text-indigo-600 dark:text-indigo-400">{{ $rekod->no_kes }}</div>
                                <div class="text-[11px] text-slate-400">{{ \Carbon\Carbon::parse($rekod->tarikh_kejadian)->format('d/m/Y h:i A') }}</div>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-slate-900 dark:text-white">{{ $rekod->murid->nama_penuh ?? '-' }}</div>
                            </td>
                            <td class="p-4">
                                <div class="font-semibold text-slate-800 dark:text-slate-200">{{ $rekod->kategoriDisiplin->nama_kategori ?? '-' }}</div>
                                <span class="px-2 py-0.5 text-[10px] rounded-full font-extrabold bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                                    {{ $rekod->tahap_kes->value ?? $rekod->tahap_kes }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 text-[11px] rounded-full font-extrabold bg-indigo-50 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300">
                                    {{ $rekod->status_kes->value ?? $rekod->status_kes }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <a href="{{ route('disiplin.show', $rekod) }}" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-200 rounded-xl text-xs font-bold transition">
                                    Butiran Kes
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400">Tiada kes disiplin dijumpai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL POPUP AI RECOMMENDATIONS -->
    <div x-show="aiModalOpen" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
        <div class="bg-white dark:bg-slate-800 rounded-2xl max-w-lg w-full p-6 space-y-4 border border-slate-200 dark:border-slate-700 shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-3">
                <h3 class="text-base font-extrabold font-heading text-slate-900 dark:text-white flex items-center gap-2">
                    <span>🤖 Cadangan Intervensi Kaunseling AI</span>
                </h3>
                <button @click="aiModalOpen = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <template x-if="aiLoading">
                <div class="py-8 text-center space-y-3">
                    <svg class="w-8 h-8 text-indigo-600 animate-spin mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <p class="text-xs text-slate-500 font-semibold">Menjana analisis PII-redacted & syor intervensi AI...</p>
                </div>
            </template>

            <template x-if="!aiLoading && aiResult">
                <div class="space-y-4 text-xs">
                    <div class="p-3.5 bg-indigo-50 dark:bg-indigo-950/60 rounded-xl border border-indigo-100 dark:border-indigo-900/60">
                        <strong class="text-indigo-900 dark:text-indigo-300 block mb-1">Ringkasan Eksekutif AI:</strong>
                        <p class="text-slate-700 dark:text-slate-300 leading-relaxed" x-text="aiResult.ringkasan_eksekutif"></p>
                    </div>

                    <div>
                        <strong class="text-slate-800 dark:text-slate-200 block mb-2">Syor Langkah Intervensi Kaunseling:</strong>
                        <ul class="space-y-1.5">
                            <template x-for="(syor, i) in aiResult.syor_intervensi_kaunseling" :key="i">
                                <li class="p-2 bg-slate-50 dark:bg-slate-700/50 rounded-lg text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                    <span class="text-indigo-500 font-bold">•</span>
                                    <span x-text="syor"></span>
                                </li>
                            </template>
                        </ul>
                    </div>

                    <div class="p-3 bg-amber-50 dark:bg-amber-950/50 rounded-xl text-[11px] text-amber-800 dark:text-amber-300 italic">
                        <strong class="block not-italic font-bold">Catatan Etika Human-in-the-Loop:</strong>
                        <span x-text="aiResult.catatan_etika"></span>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button @click="aiModalOpen = false" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold">Faham & Tutup</button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
@endsection
