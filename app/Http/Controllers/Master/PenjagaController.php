<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\UrusPenjagaRequest;
use App\Models\Murid;
use App\Models\Penjaga;
use App\Models\Sekolah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PenjagaController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Penjaga::class);

        $penjagaList = Penjaga::with(['sekolah', 'murid'])->latest()->paginate(10);
        $sekolahList = Sekolah::all();
        $muridList = Murid::all();

        return view('master.penjaga.index', compact('penjagaList', 'sekolahList', 'muridList'));
    }

    public function store(UrusPenjagaRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $muridId = $data['murid_id'] ?? null;
        $isUtama = $data['is_penjaga_utama'] ?? true;
        unset($data['murid_id'], $data['is_penjaga_utama']);

        $data['uuid'] = (string) Str::uuid();

        $penjaga = Penjaga::create($data);

        if ($muridId) {
            $penjaga->murid()->syncWithoutDetaching([
                $muridId => ['is_penjaga_utama' => $isUtama],
            ]);
        }

        return back()->with('success', 'Rekod penjaga berjaya didaftarkan.');
    }

    public function update(UrusPenjagaRequest $request, Penjaga $penjaga): RedirectResponse
    {
        $data = $request->validated();
        $muridId = $data['murid_id'] ?? null;
        $isUtama = $data['is_penjaga_utama'] ?? true;
        unset($data['murid_id'], $data['is_penjaga_utama']);

        $penjaga->update($data);

        if ($muridId) {
            $penjaga->murid()->syncWithoutDetaching([
                $muridId => ['is_penjaga_utama' => $isUtama],
            ]);
        }

        return back()->with('success', 'Maklumat penjaga berjaya dikemaskini.');
    }

    public function destroy(Penjaga $penjaga): RedirectResponse
    {
        $this->authorize('delete', $penjaga);

        $penjaga->delete();

        return back()->with('success', 'Rekod penjaga dipadam (soft delete).');
    }
}
