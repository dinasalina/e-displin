<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class Pengguna extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $table = 'pengguna';

    protected $guard_name = 'web';

    protected $fillable = [
        'uuid',
        'sekolah_id',
        'nama',
        'name',
        'no_kp',
        'email',
        'email_verified_at',
        'password',
        'jawatan',
        'status_aktif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function booted(): void
    {
        static::creating(function (Pengguna $model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->no_kp)) {
                $model->no_kp = fake()->numerify('############');
            }
            if (empty($model->nama) && ! empty($model->attributes['name'])) {
                $model->nama = $model->attributes['name'];
                unset($model->attributes['name']);
            }
        });

        static::updating(function (Pengguna $model) {
            if (isset($model->attributes['name'])) {
                $model->nama = $model->attributes['name'];
                unset($model->attributes['name']);
            }
        });
    }

    public function getNameAttribute(): string
    {
        return $this->attributes['nama'] ?? '';
    }

    public function setNameAttribute(string $value): void
    {
        $this->attributes['nama'] = $value;
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'status_aktif' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }
}
