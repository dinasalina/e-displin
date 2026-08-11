@extends('layouts.app')

@section('content')
<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-purple-800">Papan Pemuka Kelulusan Kes Berat (Sequential Approval)</h2>
        <p class="text-sm text-gray-600">Semakan dan kelulusan berperingkat kes disiplin berat oleh PK HEM dan Pengetua/Guru Besar.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-purple-50 flex justify-between items-center">
            <h3 class="font-bold text-purple-900">Senarai Kes Menunggu Kelulusan / Pengesahan</h3>
            <span class="px-2.5 py-1 bg-purple-200 text-purple-900 rounded-full text-xs font-extrabold">{{ $eskalasiList->total() }} Kes Menunggu</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">No Kes & Tarikh</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Murid & Kategori</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Peringkat Eskalasi</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Ulasan Pengesyor</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500">Tindakan Kelulusan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($eskalasiList as $eskalasi)
                        <tr>
                            <td class="px-4 py-4">
                                <div class="font-bold font-mono text-indigo-700">{{ $eskalasi->rekodDisiplin->no_kes }}</div>
                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($eskalasi->created_at)->format('d/m/Y h:i A') }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-bold text-gray-900">{{ $eskalasi->rekodDisiplin->murid->nama_penuh ?? '-' }}</div>
                                <div class="text-xs text-red-600 font-semibold">{{ $eskalasi->rekodDisiplin->kategoriDisiplin->nama_kategori ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-4">
                                @if($eskalasi->jenis_eskalasi === 'SEMAKAN_PK_HEM')
                                    <span class="px-3 py-1 bg-amber-100 text-amber-900 rounded-full text-xs font-extrabold">PERINGKAT 1: SEMAKAN PK HEM</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-900 rounded-full text-xs font-extrabold">PERINGKAT 2: PENGESAHAN PENGETUA</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-xs text-gray-700 max-w-xs">
                                <div class="font-semibold text-gray-900">Oleh: {{ $eskalasi->ditugaskanOleh->nama ?? '-' }}</div>
                                <p class="italic mt-0.5">"{{ $eskalasi->catatan_keputusan }}"</p>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div x-data="{ showModal: false }">
                                    <button @click="showModal = true" class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded text-xs font-bold">
                                        ⚡ Semak & Sahkan
                                    </button>

                                    <!-- Modal Keputusan Eskalasi -->
                                    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 text-left" style="display: none;">
                                        <div class="bg-white rounded-lg max-w-lg w-full p-6 space-y-4">
                                            <h3 class="text-lg font-bold text-gray-800">Tindakan Kelulusan: {{ $eskalasi->rekodDisiplin->no_kes }}</h3>
                                            <div class="text-xs text-gray-600 bg-gray-50 p-3 rounded border">
                                                <strong>Murid:</strong> {{ $eskalasi->rekodDisiplin->murid->nama_penuh ?? '-' }}<br>
                                                <strong>Salah Laku:</strong> {{ $eskalasi->rekodDisiplin->keterangan_kes }}
                                            </div>

                                            <form action="{{ route('disiplin.eskalasi.proses', $eskalasi) }}" method="POST" class="space-y-4">
                                                @csrf
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Keputusan Kelulusan</label>
                                                    <select name="keputusan" required class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                                                        <option value="LULUS">✅ LULUSKAN / SAHKAN</option>
                                                        <option value="TOLAK">❌ TOLAK / KEMBALIKAN KE GURU DISIPLIN</option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Catatan Rasmi / Hukuman Disyorkan</label>
                                                    <textarea name="catatan" rows="3" required placeholder="Sebab keputusan & syor tindakan lanjut..." class="mt-1 block w-full rounded-md border-gray-300 text-sm"></textarea>
                                                </div>

                                                <div class="flex justify-end space-x-2 pt-4">
                                                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm">Batal</button>
                                                    <button type="submit" class="px-5 py-2 bg-purple-600 text-white font-bold rounded-md text-sm hover:bg-purple-700">Hantar Keputusan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">Tiada kes menunggu kelulusan pada masa ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $eskalasiList->links() }}
        </div>
    </div>
</div>
@endsection
