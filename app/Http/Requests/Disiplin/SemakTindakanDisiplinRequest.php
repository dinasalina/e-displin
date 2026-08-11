<?php

namespace App\Http\Requests\Disiplin;

use App\Enums\StatusKesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class SemakTindakanDisiplinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('disiplin.semak') || $this->user()->hasRole('Super Admin');
    }

    public function rules(): array
    {
        return [
            'status_kes' => ['required', new Enum(StatusKesEnum::class)],
            'jenis_tindakan' => ['required', 'string', 'max:100'],
            'keterangan_tindakan' => ['required', 'string'],
            'tarikh_mula' => ['nullable', 'date'],
            'tarikh_tamat' => ['nullable', 'date', 'after_or_equal:tarikh_mula'],
            'catatan_kemaskini' => ['nullable', 'string'],
        ];
    }
}
