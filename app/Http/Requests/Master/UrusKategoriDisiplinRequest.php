<?php

namespace App\Http\Requests\Master;

use App\Enums\TahapKesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UrusKategoriDisiplinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('sekolah.urus') || $this->user()->hasRole('Super Admin') || $this->user()->hasRole('Pentadbir Sekolah');
    }

    public function rules(): array
    {
        return [
            'sekolah_id' => ['required', 'exists:sekolah,id'],
            'kod_kategori' => ['required', 'string', 'max:30'],
            'nama_kategori' => ['required', 'string', 'max:255'],
            'tahap_default' => ['required', new Enum(TahapKesEnum::class)],
            'penerangan' => ['nullable', 'string'],
        ];
    }
}
