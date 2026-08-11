<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UrusPenggunaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('pengguna.urus') || $this->user()->hasRole('Super Admin');
    }

    public function rules(): array
    {
        $penggunaId = $this->route('pengguna')?->id;

        return [
            'sekolah_id' => ['nullable', 'exists:sekolah,id'],
            'nama' => ['required', 'string', 'max:255'],
            'no_kp' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', 'unique:pengguna,email,'.$penggunaId],
            'password' => $penggunaId ? ['nullable', Password::defaults()] : ['required', Password::defaults()],
            'jawatan' => ['nullable', 'string', 'max:100'],
            'role' => ['required', 'exists:roles,name'],
            'status_aktif' => ['boolean'],
        ];
    }
}
