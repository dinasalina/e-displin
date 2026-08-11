<?php

namespace App\Models;

use App\Enums\JenisSekolahEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sekolah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sekolah';

    protected $fillable = [
        'uuid',
        'kod_sekolah',
        'nama_sekolah',
        'kod_ppd',
        'kod_jpn',
        'jenis_sekolah',
        'telefon',
        'emel',
        'alamat',
    ];

    protected function casts(): array
    {
        return [
            'jenis_sekolah' => JenisSekolahEnum::class,
        ];
    }

    public function tahunAkademik(): HasMany
    {
        return $this->hasMany(TahunAkademik::class, 'sekolah_id');
    }

    public function pengguna(): HasMany
    {
        return $this->hasMany(Pengguna::class, 'sekolah_id');
    }

    public function murid(): HasMany
    {
        return $this->hasMany(Murid::class, 'sekolah_id');
    }
}
