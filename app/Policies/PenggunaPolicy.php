<?php

namespace App\Policies;

use App\Models\Pengguna;

class PenggunaPolicy
{
    public function viewAny(Pengguna $user): bool
    {
        return $user->hasPermissionTo('pengguna.urus') || $user->hasRole('Super Admin');
    }

    public function create(Pengguna $user): bool
    {
        return $user->hasPermissionTo('pengguna.urus') || $user->hasRole('Super Admin');
    }

    public function update(Pengguna $user): bool
    {
        return $user->hasPermissionTo('pengguna.urus') || $user->hasRole('Super Admin');
    }

    public function delete(Pengguna $user): bool
    {
        return $user->hasPermissionTo('pengguna.urus') || $user->hasRole('Super Admin');
    }
}
