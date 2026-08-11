<?php

namespace App\Http\Requests\Disiplin;

use Illuminate\Foundation\Http\FormRequest;

class VoidKesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('disiplin.void') || $this->user()->hasRole('Super Admin');
    }

    public function rules(): array
    {
        return [
            'void_reason' => ['required', 'string', 'min:5'],
        ];
    }
}
