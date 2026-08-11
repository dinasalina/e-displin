<?php

namespace App\Models;

use App\Enums\StatusMuridEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Murid extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'murid';

    protected $fillable = [
        'uuid',
        'sekolah_id',
        'nisn_nis',
        'no_kp',
        'nama_penuh',
        'jantina',
        'tarikh_lahir',
        'status_murid',
    ];

    protected function casts(): array
    {
        return [
            'tarikh_lahir' => 'date',
            'status_murid' => StatusMuridEnum::class,
        ];
    }

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }

    public function penjaga(): BelongsToMany
    {
        return $this->belongsToMany(Penjaga::class, 'murid_penjaga', 'murid_id', 'penjaga_id')
            ->withPivot('is_penjaga_utama')
            ->withTimestamps();
    }

    public function rekodDisiplin(): HasMany
    {
        return $this->hasMany(RekodDisiplin::class, 'murid_id');
    }
}
