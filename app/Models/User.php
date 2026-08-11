<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Pengguna
{
    use SoftDeletes;
}
