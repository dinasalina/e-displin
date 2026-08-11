<?php

namespace App\Http\Controllers\Master;

use App\Enums\TahapKesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\UrusKategoriDisiplinRequest;
use App\Models\KategoriDisiplin;
use App\Models\Sekolah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class KategoriDisiplinController extends Controller
{
    public function index(): View
    {
        $kategoriList = KategoriDisiplin::with('sekolah')->latest()->paginate(10);
        $sekolahList = Sekolah::all();
        $tahapOptions = TahapKesEnum::cases();

        return view('master.kategori-disiplin.index', compact('kategoriList', 'sekolahList', 'tahapOptions'));
    }

    public function store(UrusKategoriDisiplinRequest $request): RedirectResponse
    {
        KategoriDisiplin::create(array_merge($request->validated(), [
            'uuid' => (string) Str::uuid(),
        ]));

        return back()->with('success', 'Kategori salah laku disiplin berjaya didaftarkan.');
    }

    public function update(UrusKategoriDisiplinRequest $request, KategoriDisiplin $kategoriDisiplin): RedirectResponse
    {
        $kategoriDisiplin->update($request->validated());

        return back()->with('success', 'Kategori salah laku disiplin berjaya dikemaskini.');
    }

    public function destroy(KategoriDisiplin $kategoriDisiplin): RedirectResponse
    {
        $kategoriDisiplin->delete();

        return back()->with('success', 'Kategori salah laku disiplin telah dipadam.');
    }
}
