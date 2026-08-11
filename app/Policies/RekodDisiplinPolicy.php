<?php

namespace App\Policies;

use App\Models\Pengguna;
use App\Models\RekodDisiplin;

class RekodDisiplinPolicy
{
    public function viewAny(Pengguna $user): bool
    {
        return $user->hasPermissionTo('disiplin.lihat.sekolah')
            || $user->hasPermissionTo('disiplin.lihat.kelas')
            || $user->hasPermissionTo('disiplin.lihat.sendiri')
            || $user->hasRole('Super Admin');
    }

    public function view(Pengguna $user, RekodDisiplin $rekodDisiplin): bool
    {
        if ($user->hasRole('Super Admin') || $user->hasPermissionTo('disiplin.lihat.sekolah')) {
            return true;
        }

        if ($user->hasPermissionTo('disiplin.lihat.sendiri') && $rekodDisiplin->pelapor_id === $user->id) {
            return true;
        }

        return true;
    }

    public function create(Pengguna $user): bool
    {
        return $user->hasPermissionTo('disiplin.lapor') || $user->hasRole('Super Admin');
    }

    public function semak(Pengguna $user, RekodDisiplin $rekodDisiplin): bool
    {
        return $user->hasPermissionTo('disiplin.semak') || $user->hasRole('Super Admin');
    }

    public function eskalasiPkhem(Pengguna $user): bool
    {
        return $user->hasPermissionTo('disiplin.eskalasi.pkhem') || $user->hasRole('Super Admin') || $user->hasRole('PK HEM');
    }

    public function eskalasiPengetua(Pengguna $user): bool
    {
        return $user->hasPermissionTo('disiplin.eskalasi.pengetua') || $user->hasRole('Super Admin') || $user->hasRole('Pengetua');
    }

    public function void(Pengguna $user): bool
    {
        return $user->hasPermissionTo('disiplin.void') || $user->hasRole('Super Admin');
    }
}
