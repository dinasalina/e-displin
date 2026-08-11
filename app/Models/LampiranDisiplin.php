<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LampiranDisiplin extends Model
{
    use HasFactory;

    protected $table = 'lampiran_disiplin';

    public $timestamps = false;

    protected $fillable = [
        'uuid',
        'rekod_disiplin_id',
        'nama_fail_asal',
        'path_fail',
        'mime_type',
        'saiz_bytes',
        'muat_naik_oleh_id',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'saiz_bytes' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function rekodDisiplin(): BelongsTo
    {
        return $this->belongsTo(RekodDisiplin::class, 'rekod_disiplin_id');
    }

    public function muatNaikOleh(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'muat_naik_oleh_id');
    }
}
