<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class UrusKelasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('kelas.urus') || $this->user()->hasRole('Super Admin');
    }

    public function rules(): array
    {
        return [
            'sekolah_id' => ['required', 'exists:sekolah,id'],
            'tahun_akademik_id' => ['required', 'exists:tahun_akademik,id'],
            'nama_kelas' => ['required', 'string', 'max:50'],
            'tingkatan_darjah' => ['required', 'integer', 'min:1', 'max:6'],
        ];
    }
}
