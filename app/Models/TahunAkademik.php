<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TahunAkademik extends Model
{
    use HasFactory;

    protected $table = 'tahun_akademik';

    protected $fillable = [
        'uuid',
        'sekolah_id',
        'nama_tahun',
        'tarikh_mula',
        'tarikh_tamat',
        'is_aktif',
    ];

    protected function casts(): array
    {
        return [
            'tarikh_mula' => 'date',
            'tarikh_tamat' => 'date',
            'is_aktif' => 'boolean',
        ];
    }

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }
}
