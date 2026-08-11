<?php

namespace App\Http\Controllers\Disiplin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Disiplin\EskalasiKesRequest;
use App\Models\EskalasiKes;
use App\Models\RekodDisiplin;
use App\Services\EskalasiKesService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EskalasiKesController extends Controller
{
    public function index(): View
    {
        $eskalasiList = EskalasiKes::with(['rekodDisiplin.murid', 'rekodDisiplin.kategoriDisiplin', 'ditugaskanOleh', 'penerima'])
            ->where('status', 'MENUNGGU')
            ->latest()
            ->paginate(10);

        return view('disiplin.eskalasi', compact('eskalasiList'));
    }

    public function hantarKePkhem(EskalasiKesRequest $request, RekodDisiplin $disiplin, EskalasiKesService $service): RedirectResponse
    {
        $service->hantarKePkhem($disiplin, $request->user(), $request->catatan);

        return back()->with('success', 'Kes berjaya diekalasi kepada PK HEM (Peringkat 1).');
    }

    public function prosesEskalasi(EskalasiKesRequest $request, EskalasiKes $eskalasi, EskalasiKesService $service): RedirectResponse
    {
        if ($eskalasi->jenis_eskalasi === 'SEMAKAN_PK_HEM') {
            $this->authorize('eskalasiPkhem', RekodDisiplin::class);
            $service->kelulusanPkhem($eskalasi, $request->user(), $request->keputusan, $request->catatan);
            $msg = $request->keputusan === 'LULUS' ? 'Kelulusan PK HEM direkodkan. Kes dialirkan kepada Pengetua.' : 'Kes ditolak oleh PK HEM.';
        } else {
            $this->authorize('eskalasiPengetua', RekodDisiplin::class);
            $service->pengesahanPengetua($eskalasi, $request->user(), $request->keputusan, $request->catatan);
            $msg = $request->keputusan === 'LULUS' ? 'Pengesahan Akhir Pengetua berjaya! Kes kini DITUTUP.' : 'Kes ditolak oleh Pengetua.';
        }

        return back()->with('success', $msg);
    }
}
