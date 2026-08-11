<?php

namespace App\Http\Requests\Disiplin;

use Illuminate\Foundation\Http\FormRequest;

class EskalasiKesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('disiplin.semak')
            || $this->user()->hasPermissionTo('disiplin.eskalasi.pkhem')
            || $this->user()->hasPermissionTo('disiplin.eskalasi.pengetua')
            || $this->user()->hasRole('Super Admin');
    }

    public function rules(): array
    {
        return [
            'eskalasi_id' => ['nullable', 'exists:eskalasi_kes,id'],
            'keputusan' => ['required', 'in:LULUS,TOLAK,HANTAR_PKHEM'],
            'catatan' => ['required', 'string'],
        ];
    }
}
