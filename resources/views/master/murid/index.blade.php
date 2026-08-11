@extends('layouts.app')

@section('content')
<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pengurusan Profil Murid</h2>
            <p class="text-sm text-gray-600">Pendaftaran murid, hubungan penjaga, dan penetapan status murid.</p>
        </div>
        @can('murid.urus')
        <a href="{{ route('master.murid.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-indigo-700">+ Daftar Murid Baharu</a>
        @endcan
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Nama Murid / No. KP</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">NISN / Jantina</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Sekolah</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Penjaga Utama</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-500">Status</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($muridList as $murid)
                        @php
                            $penjagaUtama = $murid->penjaga->first();
                        @endphp
                        <tr>
                            <td class="px-4 py-3">
                                <div class="font-bold text-gray-900">{{ $murid->nama_penuh }}</div>
                                <div class="text-xs text-gray-500 font-mono">KP: {{ $murid->no_kp }}</div>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600">
                                <div>NISN: {{ $murid->nisn_nis ?? '-' }}</div>
                                <div>Jantina: {{ $murid->jantina }}</div>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-700">{{ $murid->sekolah->nama_sekolah ?? '-' }}</td>
                            <td class="px-4 py-3 text-xs">
                                @if($penjagaUtama)
                                    <div class="font-semibold text-gray-800">{{ $penjagaUtama->nama_penuh }} ({{ $penjagaUtama->hubungkait }})</div>
                                    <div class="text-gray-500 font-mono">📞 {{ $penjagaUtama->no_telefon }}</div>
                                @else
                                    <span class="text-gray-400 italic">Tiada rekod penjaga</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2.5 py-1 text-xs rounded-full font-bold bg-green-100 text-green-800">
                                    {{ $murid->status_murid->value ?? $murid->status_murid }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                @can('murid.urus')
                                <a href="{{ route('master.murid.edit', $murid) }}" class="text-indigo-600 hover:text-indigo-900 text-xs font-semibold">Kemaskini</a>
                                <form action="{{ route('master.murid.destroy', $murid) }}" method="POST" class="inline" onsubmit="return confirm('Padam rekod murid ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-semibold">Padam</button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">Tiada rekod murid berdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $muridList->links() }}
        </div>
    </div>
</div>
@endsection
