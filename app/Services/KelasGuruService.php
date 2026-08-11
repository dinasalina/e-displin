<?php

namespace App\Services;

use App\Models\Kelas;
use App\Models\KelasGuru;

class KelasGuruService
{
    /**
     * Tugaskan guru ke kelas dan kemaskini tarikh tamat penugasan lama jika ada.
     */
    public function tugaskanGuru(Kelas $kelas, int $penggunaId, int $tahunAkademikId, string $peranan = 'GURU_UTAMA'): KelasGuru
    {
        // Tutup penugasan aktif terdahulu jika ada
        KelasGuru::where('kelas_id', $kelas->id)
            ->where('tahun_akademik_id', $tahunAkademikId)
            ->where('peranan', $peranan)
            ->whereNull('tarikh_tamat')
            ->update(['tarikh_tamat' => now()->toDateString()]);

        return KelasGuru::create([
            'kelas_id' => $kelas->id,
            'pengguna_id' => $penggunaId,
            'tahun_akademik_id' => $tahunAkademikId,
            'peranan' => $peranan,
            'tarikh_mula' => now()->toDateString(),
            'tarikh_tamat' => null,
        ]);
    }
}
