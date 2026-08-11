<?php

namespace App\Http\Controllers\Disiplin;

use App\Actions\Disiplin\LaporKesAction;
use App\Enums\TahapKesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Disiplin\LaporKesDisiplinRequest;
use App\Models\KategoriDisiplin;
use App\Models\Murid;
use App\Models\RekodDisiplin;
use App\Models\Sekolah;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LaporKesController extends Controller
{
    public function create(): View
    {
        $this->authorize('create', RekodDisiplin::class);

        $sekolahList = Sekolah::all();
        $muridList = Murid::with('sekolah')->where('status_murid', 'AKTIF')->get();
        $kategoriList = KategoriDisiplin::all();
        $tahapOptions = TahapKesEnum::cases();

        return view('disiplin.lapor', compact('sekolahList', 'muridList', 'kategoriList', 'tahapOptions'));
    }

    public function store(LaporKesDisiplinRequest $request, LaporKesAction $action): RedirectResponse
    {
        $failLampiran = $request->file('lampiran', []);

        $rekod = $action->execute(
            $request->user(),
            $request->validated(),
            is_array($failLampiran) ? $failLampiran : [$failLampiran]
        );

        return redirect()->route('disiplin.show', $rekod)->with('success', sprintf('Kes salah laku berjaya dilaporkan. Nombor Kes: %s', $rekod->no_kes));
    }
}
