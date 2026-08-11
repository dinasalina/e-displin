@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- PAGE HEADER & QUICK ACTIONS -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <span>Sistem e-Disiplin</span>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-indigo-600 dark:text-indigo-400 font-semibold">Dashboard Utama</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold font-heading text-slate-900 dark:text-white tracking-tight">Portal Pengurusan Disiplin Sekolah</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Selamat kembali, {{ Auth::user()->name }}. Ringkasan kes disiplin dan analitik berprediktif AI bagi SK Seri Bintang Utama.</p>
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

    <!-- 4 KAD STATISTIK UTAMA -->
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
                <span class="text-3xl font-extrabold text-slate-900 dark:text-white">1,248</span>
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950 px-2 py-0.5 rounded-full">96.4% Kehadiran</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">24 Kelas (Tingkatan 1 hingga 5)</p>
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
                <span class="text-3xl font-extrabold text-slate-900 dark:text-white">3 Kes</span>
                <span class="text-xs font-bold text-sky-600 dark:text-sky-400 bg-sky-50 dark:bg-sky-950 px-2 py-0.5 rounded-full">{{ now()->format('d M Y') }}</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">2 Kes Ringan, 1 Kes Sedang</p>
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
                <span class="text-3xl font-extrabold text-amber-600 dark:text-amber-400">7 Kes</span>
                <span class="text-xs font-bold text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950 px-2 py-0.5 rounded-full">Tindakan Disiplin</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Laporan daripada Guru Kelas</p>
        </div>

        <!-- 4. Kes Berat -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 shadow-sm relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kes Berat</span>
                <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-rose-600 dark:text-rose-400">2 Kes</span>
                <span class="text-xs font-bold text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-950 px-2 py-0.5 rounded-full">Amaran & Kaunseling</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Buli & Pergaduhan Kantin</p>
        </div>

    </div>

    <!-- WIDGET AI: "AI INSIGHT" -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-6 text-white shadow-xl border border-indigo-500/30">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>
        
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 relative z-10">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-300 text-xs font-bold">
                    <svg class="w-4 h-4 text-indigo-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>e-Disiplin AI Predictive Insight</span>
                </div>
                <h2 class="text-lg sm:text-xl font-bold font-heading">Analisis Gelagat & Ramalan Disiplin Murid</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-2 text-xs">
                    <div class="p-3 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
                        <span class="text-indigo-300 font-bold block mb-1">📈 Corak Kelewatan</span>
                        <p class="text-slate-300 text-[11px]">Kelewatan murid meningkat 18% pada Isnin pagi. Cadangan: Program Sapaan Mesra di pintu pagar utama.</p>
                    </div>
                    <div class="p-3 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
                        <span class="text-amber-300 font-bold block mb-1">⚠️ Ambang Demerit</span>
                        <p class="text-slate-300 text-[11px]">3 murid 4 Sains 1 melepasi 50 mata. Penjana Surat Amaran 2 sedia dicetak.</p>
                    </div>
                    <div class="p-3 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
                        <span class="text-emerald-300 font-bold block mb-1">💡 Impak Kaunseling</span>
                        <p class="text-slate-300 text-[11px]">Sesi kaunseling mengurangkan kes salah laku berulang sebanyak 34% bulan ini.</p>
                    </div>
                </div>
            </div>

            <div class="shrink-0 flex flex-col gap-2">
                <button class="w-full sm:w-auto px-5 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 transition-all text-center">
                    🤖 Jana Pelan Tindakan AI
                </button>
                <span class="text-[10px] text-slate-400 text-center">Enjin AI dikemaskini setiap 1 jam</span>
            </div>
        </div>
    </div>

    <!-- QUICK LINK CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('disiplin.lapor.create') }}" class="p-6 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 shadow-sm hover:border-red-500 transition group">
            <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-950 text-red-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="font-bold text-slate-900 dark:text-white text-base">Borang Lapor Kes</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftarkan insiden salah laku murid baharu secara pantas beserta lampiran bukti.</p>
        </a>

        <a href="{{ route('disiplin.index') }}" class="p-6 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 shadow-sm hover:border-indigo-500 transition group">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950 text-indigo-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="font-bold text-slate-900 dark:text-white text-base">Senarai Kes Disiplin</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Semak kronologi kes, muat naik fail bukti, dan kemaskini status hukuman murid.</p>
        </a>

        <a href="{{ route('disiplin.eskalasi.index') }}" class="p-6 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 shadow-sm hover:border-purple-500 transition group">
            <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-950 text-purple-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <h3 class="font-bold text-slate-900 dark:text-white text-base">Kelulusan Kes Berat</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Papan pemuka pengesahan berperingkat kes berat untuk PK HEM & Pengetua.</p>
        </a>
    </div>
</div>
@endsection
