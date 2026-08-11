@extends('layouts.app')

@section('content')
<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ showVoidModal: false, showTindakanModal: false, showEskalasiModal: false }">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center space-x-3">
                <h2 class="text-2xl font-bold font-mono text-indigo-700">{{ $disiplin->no_kes }}</h2>
                @if($disiplin->is_void)
                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold">VOID (BATAL)</span>
                @else
                    @php
                        $sVal = $disiplin->status_kes->value ?? $disiplin->status_kes;
                    @endphp
                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-bold">{{ $sVal }}</span>
                @endif
            </div>
            <p class="text-sm text-gray-600 mt-1">Dilaporkan pada {{ \Carbon\Carbon::parse($disiplin->created_at)->format('d/m/Y h:i A') }} oleh {{ $disiplin->pelapor->nama ?? '-' }}</p>
        </div>

        <div class="flex space-x-2">
            <a href="{{ route('disiplin.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-md hover:bg-gray-200">
                ⬅️ Kembali ke Senarai
            </a>

            @if(!$disiplin->is_void)
                @can('disiplin.semak')
                <button @click="showTindakanModal = true" class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-700">
                    + Kemaskini Status / Tindakan
                </button>
                @endcan

                @can('disiplin.semak')
                @if(($disiplin->tahap_kes->value ?? $disiplin->tahap_kes) === 'BERAT' && ($disiplin->status_kes->value ?? $disiplin->status_kes) !== 'DITUTUP')
                <button @click="showEskalasiModal = true" class="px-4 py-2 bg-amber-600 text-white text-sm font-semibold rounded-md hover:bg-amber-700">
                    ⚡ Eskalasi Ke PK HEM
                </button>
                @endif
                @endcan

                @can('disiplin.void')
                <button @click="showVoidModal = true" class="px-4 py-2 bg-red-100 text-red-700 text-sm font-semibold rounded-md hover:bg-red-200">
                    🚫 Batal Kes (Void)
                </button>
                @endcan
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Maklumat Kes & Murid -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-lg shadow border border-gray-100 space-y-4">
                <h3 class="text-lg font-bold text-gray-800 border-b pb-2">Butiran Kes Salah Laku</h3>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500 block text-xs">Murid Involved:</span>
                        <span class="font-bold text-gray-900 text-base">{{ $disiplin->murid->nama_penuh ?? '-' }}</span>
                        <span class="text-xs text-gray-500 block">KP: {{ $disiplin->murid->no_kp ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="text-gray-500 block text-xs">Kategori Salah Laku:</span>
                        <span class="font-bold text-indigo-700">{{ $disiplin->kategoriDisiplin->nama_kategori ?? '-' }}</span>
                        <span class="text-xs text-gray-500 block">Kod: {{ $disiplin->kategoriDisiplin->kod_kategori ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="text-gray-500 block text-xs">Tahap Kes:</span>
                        <span class="font-bold text-red-600">{{ $disiplin->tahap_kes->value ?? $disiplin->tahap_kes }}</span>
                    </div>

                    <div>
                        <span class="text-gray-500 block text-xs">Lokasi Kejadian:</span>
                        <span class="font-semibold text-gray-800">{{ $disiplin->lokasi_kejadian ?? '-' }}</span>
                    </div>
                </div>

                <div class="border-t pt-4">
                    <span class="text-gray-500 block text-xs mb-1">Kronologi / Keterangan Kes:</span>
                    <p class="text-sm text-gray-800 bg-gray-50 p-4 rounded border border-gray-200 whitespace-pre-line">{{ $disiplin->keterangan_kes }}</p>
                </div>

                @if($disiplin->lampiran->isNotEmpty())
                <div class="border-t pt-4">
                    <span class="text-gray-500 block text-xs mb-2">Lampiran Bukti:</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($disiplin->lampiran as $file)
                            <a href="{{ Storage::url($file->laluan_fail) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-xs font-semibold text-gray-700 rounded border">
                                📄 {{ $file->nama_fail_asal }}
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Rekod Tindakan Disiplin yang Dikenakan -->
            <div class="bg-white p-6 rounded-lg shadow border border-gray-100 space-y-4">
                <h3 class="text-lg font-bold text-gray-800 border-b pb-2">Tindakan Disiplin Dikenakan</h3>
                @forelse($disiplin->tindakan as $t)
                    <div class="p-4 bg-indigo-50/50 rounded-lg border border-indigo-100">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-indigo-900">{{ $t->jenis_tindakan }}</span>
                            <span class="text-xs text-gray-500">Oleh: {{ $t->diberiOleh->nama ?? '-' }}</span>
                        </div>
                        <p class="text-sm text-gray-700">{{ $t->keterangan }}</p>
                        @if($t->tarikh_mula)
                            <div class="text-xs text-gray-500 mt-2">Tempoh: {{ $t->tarikh_mula }} hingga {{ $t->tarikh_tamat ?? 'Selesai' }}</div>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-500 italic">Belum ada tindakan disiplin direkodkan.</p>
                @endforelse
            </div>
        </div>

        <!-- Sejarah Status & Eskalasi (Timeline) -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-lg shadow border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Sejarah Status & Audit Trail</h3>
                <div class="space-y-4 relative before:absolute before:inset-0 before:left-3 before:w-0.5 before:bg-gray-200">
                    @foreach($disiplin->sejarahStatus as $s)
                        <div class="relative pl-8">
                            <div class="absolute left-1 top-1 w-4 h-4 rounded-full bg-indigo-600 ring-4 ring-white"></div>
                            <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($s->created_at)->format('d/m/Y h:i A') }}</div>
                            <div class="font-bold text-sm text-gray-800">{{ $s->status_baharu->value ?? $s->status_baharu }}</div>
                            <div class="text-xs text-gray-600">{{ $s->catatan }}</div>
                            <div class="text-xs text-gray-400 font-semibold">Oleh: {{ $s->dikemaskiniOleh->nama ?? '-' }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Status / Tindakan -->
    <div x-show="showTindakanModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center p-4" style="display: none;">
        <div class="bg-white rounded-lg max-w-md w-full p-6 space-y-4">
            <h3 class="text-lg font-bold text-gray-800">Kemaskini Status & Tindakan</h3>
            <form action="{{ route('disiplin.tindakan.update', $disiplin) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status Baharu</label>
                    <select name="status_kes" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                        <option value="DALAM_SEMAKAN">DALAM_SEMAKAN</option>
                        <option value="DALAM_TINDAKAN">DALAM_TINDAKAN</option>
                        <option value="DITUTUP">DITUTUP</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Tindakan Dikenakan</label>
                    <input type="text" name="jenis_tindakan" required placeholder="Contoh: Amaran Bertulis / Gantung Sekolah 3 Hari" class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Keterangan Tindakan</label>
                    <textarea name="keterangan_tindakan" rows="2" required placeholder="Butiran hukuman / kaunselling..." class="mt-1 block w-full rounded-md border-gray-300 text-sm"></textarea>
                </div>
                <div class="flex justify-end space-x-2 pt-4">
                    <button type="button" @click="showTindakanModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-bold rounded-md text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Eskalasi KE PK HEM -->
    <div x-show="showEskalasiModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center p-4" style="display: none;">
        <div class="bg-white rounded-lg max-w-md w-full p-6 space-y-4">
            <h3 class="text-lg font-bold text-amber-700">Eskalasi Kes Berat ke PK HEM</h3>
            <p class="text-xs text-gray-600">Kes berat memerlukan pengesahan berperingkat PK HEM & Pengetua sebelum tindakan punitif berat dilaksanakan.</p>
            <form action="{{ route('disiplin.eskalasi.pkhem', $disiplin) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="keputusan" value="HANTAR_PKHEM">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Syor & Catatan Guru Disiplin</label>
                    <textarea name="catatan" rows="3" required placeholder="Ulasan serta syor tindakan kes berat kepada PK HEM..." class="mt-1 block w-full rounded-md border-gray-300 text-sm"></textarea>
                </div>
                <div class="flex justify-end space-x-2 pt-4">
                    <button type="button" @click="showEskalasiModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-amber-600 text-white font-bold rounded-md text-sm">Hantar Eskalasi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Void -->
    <div x-show="showVoidModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center p-4" style="display: none;">
        <div class="bg-white rounded-lg max-w-md w-full p-6 space-y-4">
            <h3 class="text-lg font-bold text-red-700">Pembatalan Kes (Void System)</h3>
            <p class="text-xs text-gray-600">Pembatalan kes adalah kekal untuk membatalkan laporan tersilap. Rekod audit akan disimpan.</p>
            <form action="{{ route('disiplin.void', $disiplin) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sebab Pembatalan (Rasmi)</label>
                    <textarea name="void_reason" rows="3" required placeholder="Nyatakan sebab pembatalan kes secara rasmi..." class="mt-1 block w-full rounded-md border-gray-300 text-sm"></textarea>
                </div>
                <div class="flex justify-end space-x-2 pt-4">
                    <button type="button" @click="showVoidModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white font-bold rounded-md text-sm">Sahkan Pembatalan (Void)</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
