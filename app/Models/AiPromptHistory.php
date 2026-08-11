<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiPromptHistory extends Model
{
    use HasFactory;

    protected $table = 'ai_prompt_history';

    public $timestamps = false;

    protected $fillable = [
        'sekolah_id',
        'pengguna_id',
        'rekod_disiplin_id',
        'provider',
        'model',
        'prompt_text',
        'response_text',
        'tokens_input',
        'tokens_output',
        'latency_ms',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'tokens_input' => 'integer',
            'tokens_output' => 'integer',
            'latency_ms' => 'integer',
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

    public function rekodDisiplin(): BelongsTo
    {
        return $this->belongsTo(RekodDisiplin::class, 'rekod_disiplin_id');
    }
}
