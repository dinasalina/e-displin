<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TindakanDisiplin extends Model
{
    use HasFactory;

    protected $table = 'tindakan_disiplin';

    public $timestamps = false;

    protected $fillable = [
        'uuid',
        'rekod_disiplin_id',
        'tetap_oleh_id',
        'diberi_oleh_id',
        'jenis_tindakan',
        'keterangan',
        'keterangan_tindakan',
        'tarikh_mula',
        'tarikh_tamat',
        'created_at',
    ];

    protected static function booted(): void
    {
        static::creating(function (TindakanDisiplin $model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'tarikh_mula' => 'date',
            'tarikh_tamat' => 'date',
            'created_at' => 'datetime',
        ];
    }

    public function getKeteranganAttribute(): ?string
    {
        return $this->attributes['keterangan_tindakan'] ?? null;
    }

    public function setKeteranganAttribute(?string $value): void
    {
        $this->attributes['keterangan_tindakan'] = $value;
    }

    public function getDiberiOlehIdAttribute(): ?int
    {
        return $this->attributes['tetap_oleh_id'] ?? null;
    }

    public function setDiberiOlehIdAttribute(?int $value): void
    {
        $this->attributes['tetap_oleh_id'] = $value;
    }

    public function rekodDisiplin(): BelongsTo
    {
        return $this->belongsTo(RekodDisiplin::class, 'rekod_disiplin_id');
    }

    public function tetapOleh(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'tetap_oleh_id');
    }

    public function diberiOleh(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'tetap_oleh_id');
    }
}
