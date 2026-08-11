<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    public $timestamps = false;

    protected $fillable = [
        'uuid',
        'sekolah_id',
        'penerima_id',
        'tajuk',
        'mesej',
        'url_tindakan',
        'is_dibaca',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'is_dibaca' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }

    public function penerima(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'penerima_id');
    }
}
