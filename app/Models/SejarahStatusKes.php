<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SejarahStatusKes extends Model
{
    use HasFactory;

    protected $table = 'sejarah_status_kes';

    public $timestamps = false;

    protected $fillable = [
        'rekod_disiplin_id',
        'dikemaskini_oleh_id',
        'status_asal',
        'status_baharu',
        'nota_perubahan',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function rekodDisiplin(): BelongsTo
    {
        return $this->belongsTo(RekodDisiplin::class, 'rekod_disiplin_id');
    }

    public function dikemaskiniOleh(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'dikemaskini_oleh_id');
    }
}
