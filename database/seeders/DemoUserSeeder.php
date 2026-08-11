<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use App\Models\Sekolah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $sekolah = Sekolah::where('kod_sekolah', 'WBA0001')->first();

        $demoUsers = [
            [
                'nama' => 'Super Administrator System',
                'no_kp' => '800101145001',
                'email' => 'admin@edisiplin.test',
                'jawatan' => 'System Architect & Admin',
                'role' => 'Super Admin',
                'sekolah_id' => null,
            ],
            [
                'nama' => 'Ahmad Razak (Pentadbir System)',
                'no_kp' => '820315105123',
                'email' => 'pentadbir@skseribintang.edu.my',
                'jawatan' => 'Pentadbir Sistem Sekolah',
                'role' => 'Pentadbir Sekolah',
                'sekolah_id' => $sekolah?->id,
            ],
            [
                'nama' => 'Mohd Azlan (Ketua Guru Disiplin)',
                'no_kp' => '850520085432',
                'email' => 'gurudisiplin@skseribintang.edu.my',
                'jawatan' => 'Ketua Guru Disiplin',
                'role' => 'Guru Disiplin',
                'sekolah_id' => $sekolah?->id,
            ],
            [
                'nama' => 'Datin Noraini (PK Hal Ehwal Murid)',
                'no_kp' => '780912146688',
                'email' => 'pkhem@skseribintang.edu.my',
                'jawatan' => 'Penolong Kanan HEM',
                'role' => 'PK HEM',
                'sekolah_id' => $sekolah?->id,
            ],
            [
                'nama' => 'Dr. Zulkifli (Pengetua / Guru Besar)',
                'no_kp' => '721104105899',
                'email' => 'pengetua@skseribintang.edu.my',
                'jawatan' => 'Guru Besar / Pengetua',
                'role' => 'Pengetua',
                'sekolah_id' => $sekolah?->id,
            ],
            [
                'nama' => 'Siti Mariam (Guru Akademik Biasa)',
                'no_kp' => '900405035222',
                'email' => 'guru1@skseribintang.edu.my',
                'jawatan' => 'Guru Akademik / Guru Kelas 5 Cemerlang',
                'role' => 'Guru',
                'sekolah_id' => $sekolah?->id,
            ],
        ];

        foreach ($demoUsers as $userData) {
            $roleName = $userData['role'];
            unset($userData['role']);

            $pengguna = Pengguna::firstOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'uuid' => (string) Str::uuid(),
                    'password' => Hash::make('password'),
                    'status_aktif' => true,
                ])
            );

            $pengguna->syncRoles([$roleName]);
        }
    }
}
