@extends('layouts.app')

@section('content')
<div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Pengguna Baharu</h2>
        <p class="text-sm text-gray-600">Daftar akaun staf sekolah dan tetapkan peranan keselamatan Spatie.</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow border border-gray-100">
        <form action="{{ route('master.pengguna.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Penuh</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @error('nama') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">No. Kad Pengenalan</label>
                    <input type="text" name="no_kp" value="{{ old('no_kp') }}" required placeholder="e.g. 850101105001" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @error('no_kp') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Alamat Emel</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Kata Laluan</label>
                    <input type="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @error('password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Sekolah</label>
                    <select name="sekolah_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">-- Pentadbir Sistem (Global) --</option>
                        @foreach($sekolahList as $s)
                            <option value="{{ $s->id }}" {{ old('sekolah_id') == $s->id ? 'selected' : '' }}>{{ $s->nama_sekolah }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Jawatan</label>
                    <input type="text" name="jawatan" value="{{ old('jawatan') }}" placeholder="e.g. Guru Disiplin / Penolong Kanan HEM" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Peranan (Role Spatie)</label>
                    <select name="role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="status_aktif" value="1" id="status_aktif" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <label for="status_aktif" class="ms-2 text-sm font-medium text-gray-700">Status Aktif</label>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('master.pengguna.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-200">Batal</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-semibold hover:bg-indigo-700">Simpan Pengguna</button>
            </div>
        </form>
    </div>
</div>
@endsection
