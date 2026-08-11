@extends('layouts.app')

@section('content')
<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Senarai Kes Disiplin</h2>
            <p class="text-sm text-gray-600">Pengurusan dan semakan rekod laporan disiplin murid sekolah.</p>
        </div>
        @can('disiplin.lapor')
        <a href="{{ route('disiplin.lapor.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white font-bold text-sm rounded-md hover:bg-red-700 shadow-sm">
            + Lapor Kes Baharu
        </a>
        @endcan
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <!-- Penapis & Carian -->
    <div class="bg-white p-4 rounded-lg shadow border border-gray-100 mb-6">
        <form method="GET" action="{{ route('disiplin.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No Kes / Nama / No KP Murid..." class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <select name="tahap" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- Semua Tahap Kes --</option>
                    @foreach($tahapOptions as $tahap)
                        <option value="{{ $tahap->value }}" {{ request('tahap') == $tahap->value ? 'selected' : '' }}>{{ $tahap->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="status" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- Semua Status --</option>
                    @foreach($statusOptions as $status)
                        <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="flex-1 bg-indigo-600 text-white font-semibold text-sm rounded-md hover:bg-indigo-700 py-2">Tapis</button>
                <a href="{{ route('disiplin.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-200">Reset</a>
            </div>
        </form>
    </div>

    <!-- Jadual Kes -->
    <div class="bg-white rounded-lg shadow border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">No Kes & Tarikh</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Murid Involved</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Kategori & Tahap</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Status Kes</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Pelapor</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($rekodList as $rekod)
                        <tr class="{{ $rekod->is_void ? 'bg-red-50/50' : '' }}">
                            <td class="px-4 py-3">
                                <div class="font-bold font-mono {{ $rekod->is_void ? 'line-through text-gray-400' : 'text-indigo-600' }}">{{ $rekod->no_kes }}</div>
                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($rekod->tarikh_kejadian)->format('d/m/Y h:i A') }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-gray-900">{{ $rekod->murid->nama_penuh ?? '-' }}</div>
                                <div class="text-xs text-gray-500">KP: {{ $rekod->murid->no_kp ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-800">{{ $rekod->kategoriDisiplin->nama_kategori ?? '-' }}</div>
                                @php
                                    $tVal = $rekod->tahap_kes->value ?? $rekod->tahap_kes;
                                    $tColor = match($tVal) {
                                        'BERAT' => 'bg-red-100 text-red-800',
                                        'SEDERHANA' => 'bg-yellow-100 text-yellow-800',
                                        default => 'bg-blue-100 text-blue-800',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 text-xs rounded font-bold {{ $tColor }}">{{ $tVal }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($rekod->is_void)
                                    <span class="px-2.5 py-1 text-xs rounded-full font-bold bg-gray-200 text-gray-700">VOID (BATAL)</span>
                                @else
                                    @php
                                        $sVal = $rekod->status_kes->value ?? $rekod->status_kes;
                                        $sColor = match($sVal) {
                                            'DILAPORKAN' => 'bg-blue-100 text-blue-800',
                                            'DALAM_SEMAKAN' => 'bg-yellow-100 text-yellow-800',
                                            'MENUNGGU_KELULUSAN' => 'bg-purple-100 text-purple-800 font-bold animate-pulse',
                                            'DALAM_TINDAKAN' => 'bg-orange-100 text-orange-800',
                                            'DITUTUP' => 'bg-green-100 text-green-800',
                                            default => 'bg-gray-100 text-gray-800',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 text-xs rounded-full font-bold {{ $sColor }}">{{ $sVal }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600">
                                {{ $rekod->pelapor->nama ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('disiplin.show', $rekod) }}" class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded text-xs font-semibold">
                                    Butiran Kes ➔
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">Tiada rekod kes disiplin dijumpai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $rekodList->links() }}
        </div>
    </div>
</div>
@endsection
