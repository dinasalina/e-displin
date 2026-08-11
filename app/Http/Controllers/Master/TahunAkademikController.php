<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\UrusTahunAkademikRequest;
use App\Models\Sekolah;
use App\Models\TahunAkademik;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TahunAkademikController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Sekolah::class);

        $tahunList = TahunAkademik::with('sekolah')->latest()->paginate(10);
        $sekolahList = Sekolah::all();

        return view('master.tahun-akademik.index', compact('tahunList', 'sekolahList'));
    }

    public function store(UrusTahunAkademikRequest $request): RedirectResponse
    {
        if ($request->boolean('is_aktif')) {
            TahunAkademik::where('sekolah_id', $request->sekolah_id)->update(['is_aktif' => false]);
        }

        TahunAkademik::create(array_merge($request->validated(), [
            'uuid' => (string) Str::uuid(),
        ]));

        return back()->with('success', 'Sesi tahun akademik berjaya ditambah.');
    }

    public function update(UrusTahunAkademikRequest $request, TahunAkademik $tahunAkademik): RedirectResponse
    {
        if ($request->boolean('is_aktif')) {
            TahunAkademik::where('sekolah_id', $tahunAkademik->sekolah_id)
                ->where('id', '!=', $tahunAkademik->id)
                ->update(['is_aktif' => false]);
        }

        $tahunAkademik->update($request->validated());

        return back()->with('success', 'Sesi tahun akademik berjaya dikemaskini.');
    }
}
