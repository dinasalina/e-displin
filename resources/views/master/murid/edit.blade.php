@extends('layouts.app')

@section('content')
<div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Kemaskini Profil Murid</h2>
        <p class="text-sm text-gray-600">Mengemaskini peribadi dan rekod kelas murid.</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow border border-gray-100">
        <form action="{{ route('master.murid.update', $murid) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sekolah</label>
                    <select name="sekolah_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @foreach($sekolahList as $s)
                            <option value="{{ $s->id }}" {{ old('sekolah_id', $murid->sekolah_id) == $s->id ? 'selected' : '' }}>{{ $s->nama_sekolah }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Penuh Murid</label>
                    <input type="text" name="nama_penuh" value="{{ old('nama_penuh', $murid->nama_penuh) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @error('nama_penuh') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">No. Kad Pengenalan / MyKid</label>
                    <input type="text" name="no_kp" value="{{ old('no_kp', $murid->no_kp) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @error('no_kp') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">NISN / No. Matrik Sekolah</label>
                    <input type="text" name="nisn_nis" value="{{ old('nisn_nis', $murid->nisn_nis) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Jantina</label>
                    <select name="jantina" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="LELAKI" {{ old('jantina', $murid->jantina) == 'LELAKI' ? 'selected' : '' }}>Lelaki</option>
                        <option value="PEREMPUAN" {{ old('jantina', $murid->jantina) == 'PEREMPUAN' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tarikh Lahir</label>
                    <input type="date" name="tarikh_lahir" value="{{ old('tarikh_lahir', $murid->tarikh_lahir) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Status Murid</label>
                    <select name="status_murid" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @foreach($statusOptions as $status)
                            <option value="{{ $status->value }}" {{ old('status_murid', $murid->status_murid->value ?? $murid->status_murid) == $status->value ? 'selected' : '' }}>{{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Penempatan Kelas Semasa</label>
                    <select name="kelas_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">-- Kekalkan / Pilih Kelas --</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }} (Darjah {{ $kelas->tingkatan_darjah }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('master.murid.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-200">Batal</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-semibold hover:bg-indigo-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
