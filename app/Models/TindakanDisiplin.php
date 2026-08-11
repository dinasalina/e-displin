<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TindakanDisiplin extends Model
{
    use HasFactory;

    protected $table = 'tindakan_disiplin';

    public $timestamps = false;

    protected $fillable = [
        'uuid',
        'rekod_disiplin_id',
        'tetap_oleh_id',
        'jenis_tindakan',
        'keterangan_tindakan',
        'tarikh_mula',
        'tarikh_tamat',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'tarikh_mula' => 'date',
            'tarikh_tamat' => 'date',
            'created_at' => 'datetime',
        ];
    }

    public function rekodDisiplin(): BelongsTo
    {
        return $this->belongsTo(RekodDisiplin::class, 'rekod_disiplin_id');
    }

    public function tetapOleh(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'tetap_oleh_id');
    }
}
