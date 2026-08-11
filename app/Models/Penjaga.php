<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penjaga extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penjaga';

    protected $fillable = [
        'uuid',
        'sekolah_id',
        'nama_penuh',
        'no_kp',
        'no_telefon',
        'email',
        'hubungkait',
    ];

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }

    public function murid(): BelongsToMany
    {
        return $this->belongsToMany(Murid::class, 'murid_penjaga', 'penjaga_id', 'murid_id')
            ->withPivot('is_penjaga_utama')
            ->withTimestamps();
    }
}
