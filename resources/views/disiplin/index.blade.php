@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <span>Modul Disiplin</span>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-indigo-600 dark:text-indigo-400 font-semibold">Rekod Kes Disiplin</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold font-heading text-slate-900 dark:text-white tracking-tight">Senarai Kes Disiplin</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Pengurusan dan semakan rekod laporan disiplin murid sekolah.</p>
        </div>

        @can('disiplin.lapor')
        <a href="{{ route('disiplin.lapor.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs sm:text-sm font-bold shadow-md shadow-rose-600/20 transition-all hover:scale-[1.02]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>+ Lapor Kes Baharu</span>
        </a>
        @endcan
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 rounded-2xl text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Penapis & Carian Card -->
    <div class="bg-white dark:bg-slate-800 p-4 sm:p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 shadow-sm">
        <form method="GET" action="{{ route('disiplin.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No Kes / Nama / No KP..." class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 rounded-xl text-xs text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div>
                <select name="tahap" class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="">-- Semua Tahap Kes --</option>
                    @foreach($tahapOptions as $tahap)
                        <option value="{{ $tahap->value }}" {{ request('tahap') == $tahap->value ? 'selected' : '' }}>{{ $tahap->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="status" class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="">-- Semua Status --</option>
                    @foreach($statusOptions as $status)
                        <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl py-2 shadow-sm transition">Tapis</button>
                <a href="{{ route('disiplin.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold hover:bg-slate-200 transition">Reset</a>
            </div>
        </form>
    </div>

    <!-- Jadual Kes Modern -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="p-4">No Kes & Tarikh</th>
                        <th class="p-4">Murid Involved</th>
                        <th class="p-4">Kategori & Tahap</th>
                        <th class="p-4">Status Kes</th>
                        <th class="p-4">Pelapor</th>
                        <th class="p-4 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-xs sm:text-sm">
                    @forelse($rekodList as $rekod)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-colors {{ $rekod->is_void ? 'bg-rose-50/20 dark:bg-rose-950/10' : '' }}">
                            <td class="p-4">
                                <div class="font-bold font-mono {{ $rekod->is_void ? 'line-through text-slate-400' : 'text-indigo-600 dark:text-indigo-400' }}">{{ $rekod->no_kes }}</div>
                                <div class="text-[11px] text-slate-400">{{ \Carbon\Carbon::parse($rekod->tarikh_kejadian)->format('d/m/Y h:i A') }}</div>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-slate-900 dark:text-white">{{ $rekod->murid->nama_penuh ?? '-' }}</div>
                                <div class="text-[11px] text-slate-400">KP: {{ $rekod->murid->no_kp ?? '-' }}</div>
                            </td>
                            <td class="p-4">
                                <div class="font-semibold text-slate-800 dark:text-slate-200">{{ $rekod->kategoriDisiplin->nama_kategori ?? '-' }}</div>
                                @php
                                    $tVal = $rekod->tahap_kes->value ?? $rekod->tahap_kes;
                                    $tColor = match($tVal) {
                                        'BERAT' => 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300',
                                        'SEDERHANA' => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300',
                                        default => 'bg-sky-100 text-sky-800 dark:bg-sky-950 dark:text-sky-300',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 text-[10px] rounded-full font-extrabold {{ $tColor }}">{{ $tVal }}</span>
                            </td>
                            <td class="p-4">
                                @if($rekod->is_void)
                                    <span class="px-2.5 py-1 text-[11px] rounded-full font-bold bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-300">VOID (BATAL)</span>
                                @else
                                    @php
                                        $sVal = $rekod->status_kes->value ?? $rekod->status_kes;
                                        $sColor = match($sVal) {
                                            'DILAPORKAN' => 'bg-sky-100 text-sky-800 dark:bg-sky-950 dark:text-sky-300',
                                            'DALAM_SEMAKAN' => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300',
                                            'MENUNGGU_KELULUSAN' => 'bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300 animate-pulse',
                                            'DALAM_TINDAKAN' => 'bg-orange-100 text-orange-800 dark:bg-orange-950 dark:text-orange-300',
                                            'DITUTUP' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300',
                                            default => 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 text-[11px] rounded-full font-extrabold {{ $sColor }}">{{ $sVal }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-xs text-slate-500 dark:text-slate-400">
                                {{ $rekod->pelapor->nama ?? '-' }}
                            </td>
                            <td class="p-4 text-right">
                                <a href="{{ route('disiplin.show', $rekod) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-50 dark:bg-indigo-950/80 text-indigo-700 dark:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-900 rounded-xl text-xs font-bold transition">
                                    <span>Butiran Kes</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Tiada rekod kes disiplin dijumpai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 dark:border-slate-700/60">
            {{ $rekodList->links() }}
        </div>
    </div>
</div>
@endsection
