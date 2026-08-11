<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class UrusKelasGuruRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('kelas.urus') || $this->user()->hasRole('Super Admin');
    }

    public function rules(): array
    {
        return [
            'kelas_id' => ['required', 'exists:kelas,id'],
            'pengguna_id' => ['required', 'exists:pengguna,id'],
            'tahun_akademik_id' => ['required', 'exists:tahun_akademik,id'],
            'peranan' => ['required', 'string', 'in:GURU_UTAMA,GURU_PENDAMPING'],
        ];
    }
}
