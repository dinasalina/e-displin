@extends('layouts.app')

@section('content')
<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pengurusan Pengguna & Peranan</h2>
            <p class="text-sm text-gray-600">Senarai akaun pentadbir, guru disiplin, PK HEM, pengetua, dan guru.</p>
        </div>
        @can('pengguna.urus')
        <a href="{{ route('master.pengguna.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-indigo-700">+ Tambah Pengguna</a>
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
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Nama / No. KP</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Emel</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Sekolah</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Peranan (Role)</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-500">Status</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($penggunaList as $pengguna)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="font-bold text-gray-900">{{ $pengguna->nama }}</div>
                                <div class="text-xs text-gray-500 font-mono">KP: {{ $pengguna->no_kp }}</div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 font-mono text-xs">{{ $pengguna->email }}</td>
                            <td class="px-4 py-3 text-xs text-gray-700">{{ $pengguna->sekolah->nama_sekolah ?? 'Pentadbir Sistem' }}</td>
                            <td class="px-4 py-3">
                                @foreach($pengguna->roles as $role)
                                    <span class="px-2.5 py-1 text-xs rounded-full font-bold bg-indigo-100 text-indigo-800 me-1">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($pengguna->status_aktif)
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-800 font-semibold">Aktif</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-800 font-semibold">Nyahaktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                @can('pengguna.urus')
                                <a href="{{ route('master.pengguna.edit', $pengguna) }}" class="text-indigo-600 hover:text-indigo-900 text-xs font-semibold">Kemaskini</a>
                                <form action="{{ route('master.pengguna.destroy', $pengguna) }}" method="POST" class="inline" onsubmit="return confirm('Nyahaktifkan pengguna ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-semibold">Nyahaktif</button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">Tiada pengguna berdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $penggunaList->links() }}
        </div>
    </div>
</div>
@endsection
