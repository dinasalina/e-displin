<?php

namespace App\Http\Controllers\Master;

use App\Enums\StatusMuridEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\UrusMuridRequest;
use App\Models\Kelas;
use App\Models\Murid;
use App\Models\Sekolah;
use App\Models\TahunAkademik;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MuridController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Murid::class);

        $muridList = Murid::with(['sekolah', 'penjaga'])->latest()->paginate(10);

        return view('master.murid.index', compact('muridList'));
    }

    public function create(): View
    {
        $this->authorize('create', Murid::class);

        $sekolahList = Sekolah::all();
        $statusOptions = StatusMuridEnum::cases();
        $kelasList = Kelas::with('tahunAkademik')->get();

        return view('master.murid.create', compact('sekolahList', 'statusOptions', 'kelasList'));
    }

    public function store(UrusMuridRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $kelasId = $data['kelas_id'] ?? null;
        unset($data['kelas_id']);

        $data['uuid'] = (string) Str::uuid();

        DB::transaction(function () use ($data, $kelasId) {
            $murid = Murid::create($data);

            if ($kelasId) {
                $tahunAkademik = TahunAkademik::where('sekolah_id', $murid->sekolah_id)->where('is_aktif', true)->first();
                if ($tahunAkademik) {
                    DB::table('sejarah_kelas_murid')->insert([
                        'murid_id' => $murid->id,
                        'kelas_id' => $kelasId,
                        'tahun_akademik_id' => $tahunAkademik->id,
                        'created_at' => now(),
                    ]);
                }
            }
        });

        return redirect()->route('master.murid.index')->with('success', 'Rekod murid berjaya didaftarkan.');
    }

    public function edit(Murid $murid): View
    {
        $this->authorize('update', $murid);

        $sekolahList = Sekolah::all();
        $statusOptions = StatusMuridEnum::cases();
        $kelasList = Kelas::with('tahunAkademik')->get();

        return view('master.murid.edit', compact('murid', 'sekolahList', 'statusOptions', 'kelasList'));
    }

    public function update(UrusMuridRequest $request, Murid $murid): RedirectResponse
    {
        $data = $request->validated();
        $kelasId = $data['kelas_id'] ?? null;
        unset($data['kelas_id']);

        DB::transaction(function () use ($murid, $data, $kelasId) {
            $murid->update($data);

            if ($kelasId) {
                $tahunAkademik = TahunAkademik::where('sekolah_id', $murid->sekolah_id)->where('is_aktif', true)->first();
                if ($tahunAkademik) {
                    DB::table('sejarah_kelas_murid')->updateOrInsert(
                        [
                            'murid_id' => $murid->id,
                            'tahun_akademik_id' => $tahunAkademik->id,
                        ],
                        [
                            'kelas_id' => $kelasId,
                            'created_at' => now(),
                        ]
                    );
                }
            }
        });

        return redirect()->route('master.murid.index')->with('success', 'Maklumat murid berjaya dikemaskini.');
    }

    public function destroy(Murid $murid): RedirectResponse
    {
        $this->authorize('delete', $murid);

        $murid->delete();

        return back()->with('success', 'Rekod murid telah dipadam (soft delete).');
    }
}
