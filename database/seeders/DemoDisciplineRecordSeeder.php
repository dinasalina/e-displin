<?php

namespace Database\Seeders;

use App\Enums\StatusKesEnum;
use App\Enums\StatusMuridEnum;
use App\Enums\TahapKesEnum;
use App\Models\KategoriDisiplin;
use App\Models\Kelas;
use App\Models\KelasGuru;
use App\Models\Murid;
use App\Models\Pengguna;
use App\Models\Penjaga;
use App\Models\RekodDisiplin;
use App\Models\Sekolah;
use App\Models\TahunAkademik;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDisciplineRecordSeeder extends Seeder
{
    public function run(): void
    {
        $sekolah = Sekolah::where('kod_sekolah', 'WBA0001')->first();
        $tahunAkademik = TahunAkademik::where('sekolah_id', $sekolah?->id)->where('is_aktif', true)->first();
        $guruDisiplin = Pengguna::where('email', 'gurudisiplin@skseribintang.edu.my')->first();
        $guru1 = Pengguna::where('email', 'guru1@skseribintang.edu.my')->first();
        $kategoriBuli = KategoriDisiplin::where('kod_kategori', 'BULI')->first();
        $kategoriPonteng = KategoriDisiplin::where('kod_kategori', 'PONTENG')->first();

        if (! $sekolah || ! $tahunAkademik || ! $guruDisiplin || ! $kategoriBuli) {
            return;
        }

        // Demo Kelas
        $kelas = Kelas::firstOrCreate(
            [
                'sekolah_id' => $sekolah->id,
                'tahun_akademik_id' => $tahunAkademik->id,
                'nama_kelas' => '5 Cemerlang',
            ],
            [
                'uuid' => (string) Str::uuid(),
                'tingkatan_darjah' => 5,
            ]
        );

        if ($guru1) {
            KelasGuru::firstOrCreate(
                [
                    'kelas_id' => $kelas->id,
                    'pengguna_id' => $guru1->id,
                    'tahun_akademik_id' => $tahunAkademik->id,
                ],
                [
                    'peranan' => 'GURU_UTAMA',
                    'tarikh_mula' => '2025-03-01',
                ]
            );
        }

        // Demo Murid
        $murid1 = Murid::firstOrCreate(
            [
                'sekolah_id' => $sekolah->id,
                'no_kp' => '140510101234',
            ],
            [
                'uuid' => (string) Str::uuid(),
                'nisn_nis' => 'NIS2025001',
                'nama_penuh' => 'Muhammad Amirul Bin Abdullah',
                'jantina' => 'LELAKI',
                'tarikh_lahir' => '2014-05-10',
                'status_murid' => StatusMuridEnum::AKTIF,
            ]
        );

        // Demo Penjaga
        $penjaga1 = Penjaga::firstOrCreate(
            [
                'sekolah_id' => $sekolah->id,
                'no_kp' => '820412105544',
            ],
            [
                'uuid' => (string) Str::uuid(),
                'nama_penuh' => 'Abdullah Bin Ishak',
                'no_telefon' => '012-3456789',
                'email' => 'abdullah@gmail.com',
                'hubungkait' => 'Bapa',
            ]
        );

        $murid1->penjaga()->syncWithoutDetaching([
            $penjaga1->id => ['is_penjaga_utama' => true],
        ]);

        // Demo Rekod Disiplin 1: Kes Buli (Berat)
        RekodDisiplin::firstOrCreate(
            [
                'no_kes' => 'KES/2026/08/0001',
            ],
            [
                'uuid' => (string) Str::uuid(),
                'sekolah_id' => $sekolah->id,
                'murid_id' => $murid1->id,
                'pelapor_id' => $guruDisiplin->id,
                'kategori_disiplin_id' => $kategoriBuli->id,
                'tahap_kes' => TahapKesEnum::BERAT,
                'status_kes' => StatusKesEnum::MENUNGGU_KELULUSAN,
                'tarikh_kejadian' => now()->subDays(2),
                'lokasi_kejadian' => 'Kantin Sekolah',
                'keterangan_kes' => 'Murid didapati mengugut dan merampas wang saku murid darjah 3 di kawasan kantin sewaktu rehat.',
                'ringkasan_ai' => 'Insiden buli fizikal dan ugutan wang saku di kantin sekolah. Memerlukan kelulusan eskalasi PK HEM & Pengetua.',
            ]
        );

        // Demo Rekod Disiplin 2: Kes Ponteng (Sederhana)
        if ($kategoriPonteng) {
            RekodDisiplin::firstOrCreate(
                [
                    'no_kes' => 'KES/2026/08/0002',
                ],
                [
                    'uuid' => (string) Str::uuid(),
                    'sekolah_id' => $sekolah->id,
                    'murid_id' => $murid1->id,
                    'pelapor_id' => $guru1?->id ?? $guruDisiplin->id,
                    'kategori_disiplin_id' => $kategoriPonteng->id,
                    'tahap_kes' => TahapKesEnum::SEDERHANA,
                    'status_kes' => StatusKesEnum::DILAPORKAN,
                    'tarikh_kejadian' => now()->subDays(1),
                    'lokasi_kejadian' => 'Bilik Darjah 5 Cemerlang',
                    'keterangan_kes' => 'Tidak hadir ke kelas Matematik selepas waktu rehat tanpa kebenaran guru.',
                ]
            );
        }
    }
}
