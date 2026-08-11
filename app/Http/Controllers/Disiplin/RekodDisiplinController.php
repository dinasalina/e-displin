<?php

namespace App\Http\Controllers\Disiplin;

use App\Enums\StatusKesEnum;
use App\Enums\TahapKesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Disiplin\SemakTindakanDisiplinRequest;
use App\Models\RekodDisiplin;
use App\Models\SejarahStatusKes;
use App\Models\TindakanDisiplin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RekodDisiplinController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', RekodDisiplin::class);

        $query = RekodDisiplin::with(['murid', 'pelapor', 'kategoriDisiplin', 'sekolah'])->latest();

        if ($request->filled('tahap')) {
            $query->where('tahap_kes', $request->tahap);
        }

        if ($request->filled('status')) {
            $query->where('status_kes', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_kes', 'like', "%{$search}%")
                    ->orWhereHas('murid', fn ($m) => $m->where('nama_penuh', 'like', "%{$search}%")->orWhere('no_kp', 'like', "%{$search}%"));
            });
        }

        $rekodList = $query->paginate(10)->withQueryString();
        $tahapOptions = TahapKesEnum::cases();
        $statusOptions = StatusKesEnum::cases();

        return view('disiplin.index', compact('rekodList', 'tahapOptions', 'statusOptions'));
    }

    public function show(RekodDisiplin $disiplin): View
    {
        $this->authorize('view', $disiplin);

        $disiplin->load([
            'murid.penjaga',
            'pelapor',
            'kategoriDisiplin',
            'lampiran',
            'sejarahStatus.dikemaskiniOleh',
            'eskalasi.ditugaskanOleh',
            'eskalasi.penerima',
            'tindakan.diberiOleh',
        ]);

        return view('disiplin.show', compact('disiplin'));
    }

    public function updateStatusTindakan(SemakTindakanDisiplinRequest $request, RekodDisiplin $disiplin): RedirectResponse
    {
        $this->authorize('semak', $disiplin);

        $statusAsal = $disiplin->status_kes;
        $statusBaharu = $request->status_kes;

        $disiplin->update(['status_kes' => $statusBaharu]);

        TindakanDisiplin::create([
            'rekod_disiplin_id' => $disiplin->id,
            'diberi_oleh_id' => $request->user()->id,
            'jenis_tindakan' => $request->jenis_tindakan,
            'keterangan' => $request->keterangan_tindakan,
            'tarikh_mula' => $request->tarikh_mula,
            'tarikh_tamat' => $request->tarikh_tamat,
        ]);

        SejarahStatusKes::create([
            'rekod_disiplin_id' => $disiplin->id,
            'status_asal' => $statusAsal,
            'status_baharu' => $statusBaharu,
            'dikemaskini_oleh_id' => $request->user()->id,
            'catatan' => $request->catatan_kemaskini ?? sprintf('Tindakan: %s', $request->jenis_tindakan),
        ]);

        return back()->with('success', 'Status kes dan tindakan disiplin berjaya dikemaskini.');
    }
}
