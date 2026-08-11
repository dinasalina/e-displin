<?php

namespace App\Models;

use App\Enums\StatusKesEnum;
use App\Enums\TahapKesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RekodDisiplin extends Model
{
    use HasFactory;

    protected $table = 'rekod_disiplin';

    protected $fillable = [
        'uuid',
        'no_kes',
        'sekolah_id',
        'murid_id',
        'pelapor_id',
        'kategori_disiplin_id',
        'tahap_kes',
        'status_kes',
        'tarikh_kejadian',
        'lokasi_kejadian',
        'keterangan_kes',
        'ringkasan_ai',
        'is_void',
        'void_reason',
        'voided_by',
        'voided_at',
        'tarikh_ditutup',
    ];

    protected function casts(): array
    {
        return [
            'tahap_kes' => TahapKesEnum::class,
            'status_kes' => StatusKesEnum::class,
            'tarikh_kejadian' => 'datetime',
            'is_void' => 'boolean',
            'voided_at' => 'datetime',
            'tarikh_ditutup' => 'datetime',
        ];
    }

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }

    public function murid(): BelongsTo
    {
        return $this->belongsTo(Murid::class, 'murid_id');
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pelapor_id');
    }

    public function kategoriDisiplin(): BelongsTo
    {
        return $this->belongsTo(KategoriDisiplin::class, 'kategori_disiplin_id');
    }

    public function voidedBy(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'voided_by');
    }

    public function eskalasiKes(): HasMany
    {
        return $this->hasMany(EskalasiKes::class, 'rekod_disiplin_id');
    }

    public function tindakanDisiplin(): HasMany
    {
        return $this->hasMany(TindakanDisiplin::class, 'rekod_disiplin_id');
    }

    public function lampiranDisiplin(): HasMany
    {
        return $this->hasMany(LampiranDisiplin::class, 'rekod_disiplin_id');
    }

    public function sejarahStatusKes(): HasMany
    {
        return $this->hasMany(SejarahStatusKes::class, 'rekod_disiplin_id');
    }
}
