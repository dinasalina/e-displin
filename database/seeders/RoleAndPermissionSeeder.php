<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'sekolah.urus',
            'pengguna.urus',
            'kelas.urus',
            'murid.urus',
            'penjaga.urus',
            'disiplin.lapor',
            'disiplin.lihat.sendiri',
            'disiplin.lihat.kelas',
            'disiplin.lihat.sekolah',
            'disiplin.semak',
            'disiplin.tindakan.ringan',
            'disiplin.eskalasi.pkhem',
            'disiplin.eskalasi.pengetua',
            'disiplin.void',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create Roles & Assign Permissions
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->givePermissionTo(Permission::all());

        $pentadbirSekolah = Role::firstOrCreate(['name' => 'Pentadbir Sekolah', 'guard_name' => 'web']);
        $pentadbirSekolah->givePermissionTo([
            'sekolah.urus',
            'pengguna.urus',
            'kelas.urus',
            'murid.urus',
            'penjaga.urus',
        ]);

        $guru = Role::firstOrCreate(['name' => 'Guru', 'guard_name' => 'web']);
        $guru->givePermissionTo([
            'disiplin.lapor',
            'disiplin.lihat.sendiri',
        ]);

        $guruKelas = Role::firstOrCreate(['name' => 'Guru Kelas', 'guard_name' => 'web']);
        $guruKelas->givePermissionTo([
            'disiplin.lapor',
            'disiplin.lihat.sendiri',
            'disiplin.lihat.kelas',
        ]);

        $guruDisiplin = Role::firstOrCreate(['name' => 'Guru Disiplin', 'guard_name' => 'web']);
        $guruDisiplin->givePermissionTo([
            'disiplin.lapor',
            'disiplin.lihat.sekolah',
            'disiplin.semak',
            'disiplin.tindakan.ringan',
            'disiplin.void',
        ]);

        $pkHem = Role::firstOrCreate(['name' => 'PK HEM', 'guard_name' => 'web']);
        $pkHem->givePermissionTo([
            'disiplin.lapor',
            'disiplin.lihat.sekolah',
            'disiplin.semak',
            'disiplin.eskalasi.pkhem',
            'disiplin.void',
        ]);

        $pengetua = Role::firstOrCreate(['name' => 'Pengetua', 'guard_name' => 'web']);
        $pengetua->givePermissionTo([
            'disiplin.lapor',
            'disiplin.lihat.sekolah',
            'disiplin.eskalasi.pengetua',
            'disiplin.void',
        ]);
    }
}
