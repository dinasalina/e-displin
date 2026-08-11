<?php

namespace App\Http\Controllers;

use App\Models\Murid;
use App\Models\RekodDisiplin;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $sekolahId = $user->sekolah_id;

        $muridQuery = Murid::query();
        $disiplinQuery = RekodDisiplin::query();

        if ($sekolahId) {
            $muridQuery->where('sekolah_id', $sekolahId);
            $disiplinQuery->where('sekolah_id', $sekolahId);
        }

        $jumlahMurid = (clone $muridQuery)->where('status_murid', 'AKTIF')->count();

        $kesHariIni = (clone $disiplinQuery)
            ->whereDate('created_at', now()->today())
            ->where('is_void', false)
            ->count();

        $kesBelumDisemak = (clone $disiplinQuery)
            ->where('status_kes', 'DILAPORKAN')
            ->where('is_void', false)
            ->count();

        $kesBerat = (clone $disiplinQuery)
            ->where('tahap_kes', 'BERAT')
            ->where('is_void', false)
            ->where('status_kes', '!=', 'DITUTUP')
            ->count();

        $senaraiKesTerkini = (clone $disiplinQuery)
            ->with(['murid', 'pelapor', 'kategoriDisiplin'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'jumlahMurid',
            'kesHariIni',
            'kesBelumDisemak',
            'kesBerat',
            'senaraiKesTerkini'
        ));
    }
}
