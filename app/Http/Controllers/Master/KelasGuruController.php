<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\UrusKelasGuruRequest;
use App\Models\Kelas;
use App\Services\KelasGuruService;
use Illuminate\Http\RedirectResponse;

class KelasGuruController extends Controller
{
    public function store(UrusKelasGuruRequest $request, KelasGuruService $service): RedirectResponse
    {
        $kelas = Kelas::findOrFail($request->kelas_id);

        $service->tugaskanGuru(
            $kelas,
            $request->pengguna_id,
            $request->tahun_akademik_id,
            $request->peranan
        );

        return back()->with('success', 'Penugasan guru kelas berjaya direkodkan.');
    }
}
