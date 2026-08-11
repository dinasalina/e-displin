<?php

namespace App\Http\Requests\Master;

use App\Enums\StatusMuridEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UrusMuridRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('murid.urus') || $this->user()->hasRole('Super Admin');
    }

    public function rules(): array
    {
        $muridId = $this->route('murid')?->id;
        $sekolahId = $this->input('sekolah_id');

        return [
            'sekolah_id' => ['required', 'exists:sekolah,id'],
            'nisn_nis' => ['nullable', 'string', 'max:30'],
            'no_kp' => [
                'required',
                'string',
                'max:20',
                Rule::unique('murid', 'no_kp')->where(fn ($query) => $query->where('sekolah_id', $sekolahId))->ignore($muridId),
            ],
            'nama_penuh' => ['required', 'string', 'max:255'],
            'jantina' => ['required', 'in:LELAKI,PEREMPUAN'],
            'tarikh_lahir' => ['required', 'date'],
            'status_murid' => ['required', new Enum(StatusMuridEnum::class)],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
        ];
    }
}
