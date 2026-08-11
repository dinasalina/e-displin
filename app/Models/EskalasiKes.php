<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EskalasiKes extends Model
{
    use HasFactory;

    protected $table = 'eskalasi_kes';

    protected $fillable = [
        'uuid',
        'rekod_disiplin_id',
        'ditugaskan_oleh_id',
        'penerima_id',
        'jenis_eskalasi',
        'status',
        'catatan',
        'keputusan',
        'catatan_keputusan',
        'ditugaskan_pada',
        'diputuskan_pada',
    ];

    protected function casts(): array
    {
        return [
            'ditugaskan_pada' => 'datetime',
            'diputuskan_pada' => 'datetime',
        ];
    }

    public function rekodDisiplin(): BelongsTo
    {
        return $this->belongsTo(RekodDisiplin::class, 'rekod_disiplin_id');
    }

    public function ditugaskanOleh(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'ditugaskan_oleh_id');
    }

    public function penerima(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'penerima_id');
    }
}
