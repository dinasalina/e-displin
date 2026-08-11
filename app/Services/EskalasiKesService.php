<?php

namespace App\Services;

use App\Enums\StatusKesEnum;
use App\Models\EskalasiKes;
use App\Models\Pengguna;
use App\Models\RekodDisiplin;
use App\Models\SejarahStatusKes;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EskalasiKesService
{
    /**
     * Memulakan eskalasi kes berat daripada Guru Disiplin kepada PK HEM.
     */
    public function hantarKePkhem(RekodDisiplin $rekod, Pengguna $guruDisiplin, ?string $catatan = null): EskalasiKes
    {
        return DB::transaction(function () use ($rekod, $guruDisiplin, $catatan) {
            $pkHemUser = User::role('PK HEM')->where('sekolah_id', $rekod->sekolah_id)->first();
            $penerimaId = $pkHemUser?->id ?? $guruDisiplin->id;

            $eskalasi = EskalasiKes::create([
                'uuid' => (string) Str::uuid(),
                'rekod_disiplin_id' => $rekod->id,
                'ditugaskan_oleh_id' => $guruDisiplin->id,
                'penerima_id' => $penerimaId,
                'jenis_eskalasi' => 'SEMAKAN_PK_HEM',
                'status' => 'MENUNGGU',
                'catatan_keputusan' => $catatan,
                'ditugaskan_pada' => now(),
            ]);

            $rekod->update(['status_kes' => StatusKesEnum::MENUNGGU_KELULUSAN]);

            SejarahStatusKes::create([
                'rekod_disiplin_id' => $rekod->id,
                'status_asal' => $rekod->status_kes,
                'status_baharu' => StatusKesEnum::MENUNGGU_KELULUSAN,
                'dikemaskini_oleh_id' => $guruDisiplin->id,
                'catatan' => 'Eskalasi Peringkat 1 (Semakan PK HEM): '.($catatan ?? 'Menunggu tindakan PK HEM'),
            ]);

            return $eskalasi;
        });
    }

    /**
     * Keputusan PK HEM: Mengeluluskan Peringkat 1 dan mengalirkan ke Pengetua (Peringkat 2).
     */
    public function kelulusanPkhem(EskalasiKes $eskalasiPkhem, Pengguna $pkHem, string $keputusan, string $catatan): EskalasiKes
    {
        return DB::transaction(function () use ($eskalasiPkhem, $pkHem, $keputusan, $catatan) {
            $eskalasiPkhem->update([
                'penerima_id' => $pkHem->id,
                'status' => $keputusan === 'LULUS' ? 'DILULUSKAN' : 'DITOLAK',
                'catatan_keputusan' => $catatan,
                'diputuskan_pada' => now(),
            ]);

            $rekod = $eskalasiPkhem->rekodDisiplin;

            if ($keputusan === 'LULUS') {
                $pengetuaUser = User::role('Pengetua')->where('sekolah_id', $rekod->sekolah_id)->first();
                $penerimaPengetuaId = $pengetuaUser?->id ?? $pkHem->id;

                // Cipta eskalasi Peringkat 2 untuk Pengetua
                $eskalasiPengetua = EskalasiKes::create([
                    'uuid' => (string) Str::uuid(),
                    'rekod_disiplin_id' => $rekod->id,
                    'ditugaskan_oleh_id' => $pkHem->id,
                    'penerima_id' => $penerimaPengetuaId,
                    'jenis_eskalasi' => 'PENGESAHAN_PENGETUA',
                    'status' => 'MENUNGGU',
                    'catatan_keputusan' => 'Disokong oleh PK HEM: '.$catatan,
                    'ditugaskan_pada' => now(),
                ]);

                SejarahStatusKes::create([
                    'rekod_disiplin_id' => $rekod->id,
                    'status_asal' => StatusKesEnum::MENUNGGU_KELULUSAN,
                    'status_baharu' => StatusKesEnum::MENUNGGU_KELULUSAN,
                    'dikemaskini_oleh_id' => $pkHem->id,
                    'catatan' => 'Diluluskan PK HEM (Peringkat 1). Dialirkan ke Pengetua/Guru Besar.',
                ]);

                return $eskalasiPengetua;
            } else {
                $rekod->update(['status_kes' => StatusKesEnum::DALAM_TINDAKAN]);

                SejarahStatusKes::create([
                    'rekod_disiplin_id' => $rekod->id,
                    'status_asal' => StatusKesEnum::MENUNGGU_KELULUSAN,
                    'status_baharu' => StatusKesEnum::DALAM_TINDAKAN,
                    'dikemaskini_oleh_id' => $pkHem->id,
                    'catatan' => 'Ditolak oleh PK HEM: '.$catatan,
                ]);

                return $eskalasiPkhem;
            }
        });
    }

    /**
     * Keputusan Pengesahan Akhir Pengetua (Peringkat 2).
     */
    public function pengesahanPengetua(EskalasiKes $eskalasiPengetua, Pengguna $pengetua, string $keputusan, string $catatan): EskalasiKes
    {
        return DB::transaction(function () use ($eskalasiPengetua, $pengetua, $keputusan, $catatan) {
            $eskalasiPengetua->update([
                'penerima_id' => $pengetua->id,
                'status' => $keputusan === 'LULUS' ? 'DILULUSKAN' : 'DITOLAK',
                'catatan_keputusan' => $catatan,
                'diputuskan_pada' => now(),
            ]);

            $rekod = $eskalasiPengetua->rekodDisiplin;

            $statusBaharu = $keputusan === 'LULUS' ? StatusKesEnum::DITUTUP : StatusKesEnum::DALAM_TINDAKAN;
            $rekod->update(['status_kes' => $statusBaharu]);

            SejarahStatusKes::create([
                'rekod_disiplin_id' => $rekod->id,
                'status_asal' => StatusKesEnum::MENUNGGU_KELULUSAN,
                'status_baharu' => $statusBaharu,
                'dikemaskini_oleh_id' => $pengetua->id,
                'catatan' => 'Pengesahan Akhir Pengetua (Peringkat 2): '.$catatan,
            ]);

            return $eskalasiPengetua;
        });
    }
}
