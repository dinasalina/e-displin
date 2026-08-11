<?php

namespace Database\Seeders;

use App\Enums\TahapKesEnum;
use App\Models\KategoriDisiplin;
use App\Models\Sekolah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KategoriDisiplinSeeder extends Seeder
{
    public function run(): void
    {
        $sekolah = Sekolah::where('kod_sekolah', 'WBA0001')->first();

        if (! $sekolah) {
            return;
        }

        $kategoriList = [
            [
                'kod_kategori' => 'PONTENG',
                'nama_kategori' => 'Ponteng Sekolah / Kelas',
                'tahap_default' => TahapKesEnum::SEDERHANA,
                'penerangan' => 'Tidak hadir ke sekolah atau kelas tanpa kenyataan/sebab munasabah.',
            ],
            [
                'kod_kategori' => 'BULI',
                'nama_kategori' => 'Buli Fizikal / Siber',
                'tahap_default' => TahapKesEnum::BERAT,
                'penerangan' => 'Tindakan mengugut, mencederakan, atau membuli murid lain secara fizikal atau digital.',
            ],
            [
                'kod_kategori' => 'VANDALISME',
                'nama_kategori' => 'Kerosakan Harta Benda Sekolah',
                'tahap_default' => TahapKesEnum::SEDERHANA,
                'penerangan' => 'Merosakkan perabot, fasiliti, atau peralatan sekolah.',
            ],
            [
                'kod_kategori' => 'BIADAB',
                'nama_kategori' => 'Tingkah Laku Biadab / Kurang Sopan',
                'tahap_default' => TahapKesEnum::RINGAN,
                'penerangan' => 'Menggunakan bahasa kesat atau menunjukkan sikap tidak menghormati guru.',
            ],
            [
                'kod_kategori' => 'MEROKOK',
                'nama_kategori' => 'Membawa / Memiliki / Merokok / Vape',
                'tahap_default' => TahapKesEnum::BERAT,
                'penerangan' => 'Membawa atau menggunakan bahan terlarang seperti rokok atau vape di kawasan sekolah.',
            ],
            [
                'kod_kategori' => 'DRESSCODE',
                'nama_kategori' => 'Kekemasan Diri & Pakaian',
                'tahap_default' => TahapKesEnum::RINGAN,
                'penerangan' => 'Melanggar etika berpakaian sekolah, fesyen rambut, atau perhiasan.',
            ],
            [
                'kod_kategori' => 'LEWAT',
                'nama_kategori' => 'Hadir Lewat Ke Sekolah',
                'tahap_default' => TahapKesEnum::RINGAN,
                'penerangan' => 'Tiba di sekolah selepas waktu perhimpunan / pintu pagar ditutup.',
            ],
        ];

        foreach ($kategoriList as $kategori) {
            KategoriDisiplin::firstOrCreate(
                [
                    'sekolah_id' => $sekolah->id,
                    'kod_kategori' => $kategori['kod_kategori'],
                ],
                [
                    'uuid' => (string) Str::uuid(),
                    'nama_kategori' => $kategori['nama_kategori'],
                    'tahap_default' => $kategori['tahap_default'],
                    'penerangan' => $kategori['penerangan'],
                ]
            );
        }
    }
}
