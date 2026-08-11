@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
            <span>Modul Disiplin</span>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-purple-600 dark:text-purple-400 font-semibold">Sequential Approval</span>
        </div>
        <h1 class="text-xl sm:text-2xl font-extrabold font-heading text-slate-900 dark:text-white tracking-tight">Papan Pemuka Kelulusan Kes Berat</h1>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Semakan dan kelulusan berperingkat kes disiplin berat oleh PK HEM dan Pengetua/Guru Besar.</p>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 rounded-2xl text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-700/60 bg-purple-50/50 dark:bg-purple-950/30 flex justify-between items-center">
            <h3 class="font-extrabold text-purple-900 dark:text-purple-300 text-sm sm:text-base">Senarai Kes Menunggu Kelulusan / Pengesahan</h3>
            <span class="px-3 py-1 bg-purple-200 dark:bg-purple-900 text-purple-900 dark:text-purple-200 rounded-full text-xs font-extrabold">{{ $eskalasiList->total() }} Kes Menunggu</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="p-4">No Kes & Tarikh</th>
                        <th class="p-4">Murid & Kategori</th>
                        <th class="p-4">Peringkat Eskalasi</th>
                        <th class="p-4">Ulasan Pengesyor</th>
                        <th class="p-4 text-right">Tindakan Kelulusan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-xs sm:text-sm">
                    @forelse($eskalasiList as $eskalasi)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="p-4">
                                <div class="font-bold font-mono text-indigo-600 dark:text-indigo-400">{{ $eskalasi->rekodDisiplin->no_kes }}</div>
                                <div class="text-[11px] text-slate-400">{{ \Carbon\Carbon::parse($eskalasi->created_at)->format('d/m/Y h:i A') }}</div>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-slate-900 dark:text-white">{{ $eskalasi->rekodDisiplin->murid->nama_penuh ?? '-' }}</div>
                                <div class="text-xs text-rose-600 dark:text-rose-400 font-semibold">{{ $eskalasi->rekodDisiplin->kategoriDisiplin->nama_kategori ?? '-' }}</div>
                            </td>
                            <td class="p-4">
                                @if($eskalasi->jenis_eskalasi === 'SEMAKAN_PK_HEM')
                                    <span class="px-3 py-1 bg-amber-100 text-amber-900 dark:bg-amber-950 dark:text-amber-300 rounded-full text-[11px] font-extrabold">PERINGKAT 1: SEMAKAN PK HEM</span>
                                @else
                                    <span class="px-3 py-1 bg-rose-100 text-rose-900 dark:bg-rose-950 dark:text-rose-300 rounded-full text-[11px] font-extrabold">PERINGKAT 2: PENGESAHAN PENGETUA</span>
                                @endif
                            </td>
                            <td class="p-4 text-xs text-slate-700 dark:text-slate-300 max-w-xs">
                                <div class="font-bold text-slate-900 dark:text-white">Oleh: {{ $eskalasi->ditugaskanOleh->nama ?? '-' }}</div>
                                <p class="italic mt-0.5 text-slate-500">"{{ $eskalasi->catatan_keputusan }}"</p>
                            </td>
                            <td class="p-4 text-right">
                                <div x-data="{ showModal: false }">
                                    <button @click="showModal = true" class="px-3.5 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow-md shadow-purple-600/20 transition">
                                        ⚡ Semak & Sahkan
                                    </button>

                                    <!-- Modal Keputusan Eskalasi -->
                                    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 text-left" style="display: none;">
                                        <div class="bg-white dark:bg-slate-800 rounded-2xl max-w-lg w-full p-6 space-y-4 border border-slate-200 dark:border-slate-700 shadow-2xl">
                                            <h3 class="text-base font-extrabold font-heading text-slate-900 dark:text-white">Tindakan Kelulusan: {{ $eskalasi->rekodDisiplin->no_kes }}</h3>
                                            <div class="text-xs text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-700/50 p-3.5 rounded-xl border border-slate-200/60 dark:border-slate-600/50">
                                                <strong>Murid:</strong> {{ $eskalasi->rekodDisiplin->murid->nama_penuh ?? '-' }}<br>
                                                <strong>Salah Laku:</strong> {{ $eskalasi->rekodDisiplin->keterangan_kes }}
                                            </div>

                                            <form action="{{ route('disiplin.eskalasi.proses', $eskalasi) }}" method="POST" class="space-y-4">
                                                @csrf
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1">Keputusan Kelulusan</label>
                                                    <select name="keputusan" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-200 outline-none">
                                                        <option value="LULUS">✅ LULUSKAN / SAHKAN</option>
                                                        <option value="TOLAK">❌ TOLAK / KEMBALIKAN KE GURU DISIPLIN</option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1">Catatan Rasmi / Hukuman Disyorkan</label>
                                                    <textarea name="catatan" rows="3" required placeholder="Sebab keputusan & syor tindakan lanjut..." class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-200 outline-none"></textarea>
                                                </div>

                                                <div class="flex justify-end space-x-2 pt-4 border-t border-slate-100 dark:border-slate-700">
                                                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold">Batal</button>
                                                    <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white font-extrabold rounded-xl text-xs shadow-md">Hantar Keputusan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400">Tiada kes menunggu kelulusan pada masa ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 dark:border-slate-700/60">
            {{ $eskalasiList->links() }}
        </div>
    </div>
</div>
@endsection
