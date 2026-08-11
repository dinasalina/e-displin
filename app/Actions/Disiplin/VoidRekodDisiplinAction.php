<?php

namespace App\Actions\Disiplin;

use App\Models\AktivitiLog;
use App\Models\Pengguna;
use App\Models\RekodDisiplin;
use App\Models\SejarahStatusKes;
use Illuminate\Support\Facades\DB;

class VoidRekodDisiplinAction
{
    /**
     * Membatalkan rekod disiplin tersilap (Void System).
     */
    public function execute(Pengguna $pengurus, RekodDisiplin $rekod, string $voidReason): RekodDisiplin
    {
        return DB::transaction(function () use ($pengurus, $rekod, $voidReason) {
            $statusAsal = $rekod->status_kes;

            $rekod->update([
                'is_void' => true,
                'void_reason' => $voidReason,
                'void_by' => $pengurus->id,
                'void_at' => now(),
            ]);

            SejarahStatusKes::create([
                'rekod_disiplin_id' => $rekod->id,
                'status_asal' => $statusAsal,
                'status_baharu' => $statusAsal,
                'dikemaskini_oleh_id' => $pengurus->id,
                'catatan' => 'PEMBATALAN KES (VOID): '.$voidReason,
            ]);

            AktivitiLog::create([
                'sekolah_id' => $rekod->sekolah_id,
                'pengguna_id' => $pengurus->id,
                'jenis_aktiviti' => 'VOID_KES',
                'penerangan' => sprintf('Kes %s telah dibatalkan oleh %s. Sebab: %s', $rekod->no_kes, $pengurus->nama ?? $pengurus->name, $voidReason),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return $rekod;
        });
    }
}
