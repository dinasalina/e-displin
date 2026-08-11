<?php

namespace Database\Seeders;

use App\Enums\JenisSekolahEnum;
use App\Models\Sekolah;
use App\Models\TahunAkademik;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SekolahSeeder extends Seeder
{
    public function run(): void
    {
        $sekolah = Sekolah::firstOrCreate(
            ['kod_sekolah' => 'WBA0001'],
            [
                'uuid' => (string) Str::uuid(),
                'nama_sekolah' => 'SK Seri Bintang Utama',
                'kod_ppd' => 'PPD01',
                'kod_jpn' => 'JPN01',
                'jenis_sekolah' => JenisSekolahEnum::RENDAH,
                'telefon' => '03-91234567',
                'emel' => 'wba0001@moe.edu.my',
                'alamat' => 'Jalan Seri Bintang, Cheras, 56100 Kuala Lumpur',
            ]
        );

        TahunAkademik::firstOrCreate(
            [
                'sekolah_id' => $sekolah->id,
                'nama_tahun' => '2025/2026',
            ],
            [
                'uuid' => (string) Str::uuid(),
                'tarikh_mula' => '2025-03-01',
                'tarikh_tamat' => '2026-02-28',
                'is_aktif' => true,
            ]
        );
    }
}
