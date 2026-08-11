<?php

namespace App\Policies;

use App\Models\Pengguna;

class SekolahPolicy
{
    public function viewAny(Pengguna $user): bool
    {
        return $user->hasPermissionTo('sekolah.urus') || $user->hasRole('Super Admin');
    }

    public function create(Pengguna $user): bool
    {
        return $user->hasPermissionTo('sekolah.urus') || $user->hasRole('Super Admin');
    }

    public function update(Pengguna $user): bool
    {
        return $user->hasPermissionTo('sekolah.urus') || $user->hasRole('Super Admin');
    }

    public function delete(Pengguna $user): bool
    {
        return $user->hasRole('Super Admin');
    }
}
