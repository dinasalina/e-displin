<?php

namespace App\Models;

use App\Enums\TahapKesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriDisiplin extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kategori_disiplin';

    protected $fillable = [
        'uuid',
        'sekolah_id',
        'kod_kategori',
        'nama_kategori',
        'tahap_default',
        'penerangan',
    ];

    protected function casts(): array
    {
        return [
            'tahap_default' => TahapKesEnum::class,
        ];
    }

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }
}
