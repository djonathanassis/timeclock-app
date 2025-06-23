<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use App\Enums\JobPosition;
use App\Enums\UserRole;
use App\Rules\ValidCpf;
use App\Rules\ValidZipCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Enum;

class StoreUserRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'cpf'          => ['required', 'string', 'unique:users', new ValidCpf()],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
            'job_position' => ['required', new Enum(JobPosition::class)],
            'role'         => ['required', new Enum(UserRole::class)],
            'birth_date'   => ['required', 'date', 'before:today'],
            'zip_code'     => ['required', 'string', new ValidZipCode()],
            'street'       => ['required', 'string', 'max:255'],
            'number'       => ['nullable', 'string', 'max:20'],
            'complement'   => ['nullable', 'string', 'max:255'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'city'         => ['required', 'string', 'max:255'],
            'state'        => ['required', 'string', 'size:2'],
            'manager_id'   => ['nullable', 'exists:users,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'manager_id' => $this->user()?->id,
            'password'   => Hash::make((string) $this->input('password')),
            'cpf'        => preg_replace('/\D/', '', (string) $this->input('cpf')),
            'zip_code'   => preg_replace('/\D/', '', (string) $this->input('zip_code')),
        ]);
    }
}
