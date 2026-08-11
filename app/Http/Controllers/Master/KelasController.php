<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\UrusKelasRequest;
use App\Models\Kelas;
use App\Models\Pengguna;
use App\Models\Sekolah;
use App\Models\TahunAkademik;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class KelasController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Sekolah::class);

        $kelasList = Kelas::with(['sekolah', 'tahunAkademik', 'kelasGuru.pengguna'])->latest()->paginate(10);
        $sekolahList = Sekolah::all();
        $tahunAkademikList = TahunAkademik::where('is_aktif', true)->get();
        $guruList = Pengguna::whereHas('roles', fn ($q) => $q->whereIn('name', ['Guru', 'Guru Kelas', 'Guru Disiplin']))->get();

        return view('master.kelas.index', compact('kelasList', 'sekolahList', 'tahunAkademikList', 'guruList'));
    }

    public function store(UrusKelasRequest $request): RedirectResponse
    {
        Kelas::create(array_merge($request->validated(), [
            'uuid' => (string) Str::uuid(),
        ]));

        return back()->with('success', 'Rekod kelas baharu berjaya didaftarkan.');
    }

    public function update(UrusKelasRequest $request, Kelas $kela): RedirectResponse
    {
        $kela->update($request->validated());

        return back()->with('success', 'Maklumat kelas berjaya dikemaskini.');
    }

    public function destroy(Kelas $kela): RedirectResponse
    {
        $kela->delete();

        return back()->with('success', 'Rekod kelas telah dipadam.');
    }
}
