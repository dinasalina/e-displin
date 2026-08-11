<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AktivitiLog extends Model
{
    use HasFactory;

    protected $table = 'aktiviti_log';

    public $timestamps = false;

    protected $fillable = [
        'sekolah_id',
        'pengguna_id',
        'jenis_aktiviti',
        'penerangan',
        'ip_address',
        'user_agent',
        'data_lama',
        'data_baharu',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'data_lama' => 'array',
            'data_baharu' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }
}
