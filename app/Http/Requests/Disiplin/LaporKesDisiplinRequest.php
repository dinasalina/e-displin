<?php

namespace App\Http\Requests\Disiplin;

use App\Enums\TahapKesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class LaporKesDisiplinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('disiplin.lapor') || $this->user()->hasRole('Super Admin');
    }

    public function rules(): array
    {
        return [
            'sekolah_id' => ['required', 'exists:sekolah,id'],
            'murid_id' => ['required', 'exists:murid,id'],
            'kategori_disiplin_id' => ['required', 'exists:kategori_disiplin,id'],
            'tahap_kes' => ['nullable', new Enum(TahapKesEnum::class)],
            'tarikh_kejadian' => ['required', 'date'],
            'lokasi_kejadian' => ['nullable', 'string', 'max:255'],
            'keterangan_kes' => ['required', 'string'],
            'lampiran.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }
}
