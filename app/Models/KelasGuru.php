<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KelasGuru extends Model
{
    use HasFactory;

    protected $table = 'kelas_guru';

    protected $fillable = [
        'kelas_id',
        'pengguna_id',
        'tahun_akademik_id',
        'peranan',
        'tarikh_mula',
        'tarikh_tamat',
    ];

    protected function casts(): array
    {
        return [
            'tarikh_mula' => 'date',
            'tarikh_tamat' => 'date',
        ];
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function tahunAkademik(): BelongsTo
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id');
    }
}
