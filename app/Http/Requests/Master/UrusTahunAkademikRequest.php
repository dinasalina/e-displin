<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class UrusTahunAkademikRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('sekolah.urus') || $this->user()->hasRole('Super Admin');
    }

    public function rules(): array
    {
        return [
            'sekolah_id' => ['required', 'exists:sekolah,id'],
            'nama_tahun' => ['required', 'string', 'max:50'],
            'tarikh_mula' => ['required', 'date'],
            'tarikh_tamat' => ['required', 'date', 'after:tarikh_mula'],
            'is_aktif' => ['boolean'],
        ];
    }
}
