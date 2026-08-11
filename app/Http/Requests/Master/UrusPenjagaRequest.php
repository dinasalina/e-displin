<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class UrusPenjagaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('penjaga.urus') || $this->user()->hasRole('Super Admin');
    }

    public function rules(): array
    {
        return [
            'sekolah_id' => ['required', 'exists:sekolah,id'],
            'nama_penuh' => ['required', 'string', 'max:255'],
            'no_kp' => ['required', 'string', 'max:20'],
            'no_telefon' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'hubungkait' => ['required', 'string', 'max:50'],
            'murid_id' => ['nullable', 'exists:murid,id'],
            'is_penjaga_utama' => ['boolean'],
        ];
    }
}
