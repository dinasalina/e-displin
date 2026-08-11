<?php

namespace App\Http\Requests\Master;

use App\Enums\JenisSekolahEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UrusSekolahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('sekolah.urus') || $this->user()->hasRole('Super Admin');
    }

    public function rules(): array
    {
        $sekolahId = $this->route('sekolah')?->id;

        return [
            'kod_sekolah' => ['required', 'string', 'max:20', 'unique:sekolah,kod_sekolah,'.$sekolahId],
            'nama_sekolah' => ['required', 'string', 'max:255'],
            'kod_ppd' => ['nullable', 'string', 'max:20'],
            'kod_jpn' => ['nullable', 'string', 'max:20'],
            'jenis_sekolah' => ['required', new Enum(JenisSekolahEnum::class)],
            'telefon' => ['nullable', 'string', 'max:20'],
            'emel' => ['nullable', 'email', 'max:100'],
            'alamat' => ['nullable', 'string'],
        ];
    }
}
