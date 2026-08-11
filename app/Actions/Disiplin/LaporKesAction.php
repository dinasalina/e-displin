<?php

namespace App\Actions\Disiplin;

use App\Enums\StatusKesEnum;
use App\Enums\TahapKesEnum;
use App\Models\KategoriDisiplin;
use App\Models\LampiranDisiplin;
use App\Models\Pengguna;
use App\Models\RekodDisiplin;
use App\Models\SejarahStatusKes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LaporKesAction
{
    /**
     * Merekodkan laporan kes salah laku baharu.
     */
    public function execute(Pengguna $pelapor, array $data, array $failLampiran = []): RekodDisiplin
    {
        return DB::transaction(function () use ($pelapor, $data, $failLampiran) {
            $year = now()->format('Y');
            $month = now()->format('m');
            $lastRecord = RekodDisiplin::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->latest('id')
                ->first();

            $sequence = $lastRecord ? ((int) Str::afterLast($lastRecord->no_kes, '/') + 1) : 1;
            $noKes = sprintf('KES/%s/%s/%04d', $year, $month, $sequence);

            $kategori = KategoriDisiplin::findOrFail($data['kategori_disiplin_id']);
            $tahapKes = $data['tahap_kes'] ?? $kategori->tahap_default;

            $statusKes = ($tahapKes === TahapKesEnum::BERAT || (is_object($tahapKes) && $tahapKes->value === 'BERAT') || $tahapKes === 'BERAT')
                ? StatusKesEnum::MENUNGGU_KELULUSAN
                : StatusKesEnum::DILAPORKAN;

            $rekod = RekodDisiplin::create([
                'uuid' => (string) Str::uuid(),
                'no_kes' => $noKes,
                'sekolah_id' => $pelapor->sekolah_id ?? $data['sekolah_id'],
                'murid_id' => $data['murid_id'],
                'pelapor_id' => $pelapor->id,
                'kategori_disiplin_id' => $kategori->id,
                'tahap_kes' => $tahapKes,
                'status_kes' => $statusKes,
                'tarikh_kejadian' => $data['tarikh_kejadian'],
                'lokasi_kejadian' => $data['lokasi_kejadian'] ?? 'Kawasan Sekolah',
                'keterangan_kes' => $data['keterangan_kes'],
            ]);

            // Merekod Sejarah Status Awal
            SejarahStatusKes::create([
                'rekod_disiplin_id' => $rekod->id,
                'status_asal' => null,
                'status_baharu' => $statusKes,
                'dikemaskini_oleh_id' => $pelapor->id,
                'catatan' => 'Kes dilaporkan dalam sistem.',
            ]);

            // Memuat naik fail lampiran bukti jika ada
            foreach ($failLampiran as $file) {
                if ($file instanceof UploadedFile) {
                    $path = $file->store('lampiran_disiplin', 'public');
                    LampiranDisiplin::create([
                        'rekod_disiplin_id' => $rekod->id,
                        'nama_fail_asal' => $file->getClientOriginalName(),
                        'laluan_fail' => $path,
                        'jenis_mime' => $file->getClientMimeType(),
                        'saiz_fail_bytes' => $file->getSize(),
                    ]);
                }
            }

            return $rekod;
        });
    }
}
