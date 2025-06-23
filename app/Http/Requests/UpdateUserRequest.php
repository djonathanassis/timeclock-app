<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use App\Enums\UserRole;
use App\Models\User;
use App\Rules\ValidCpf;
use App\Rules\ValidZipCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateUserRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var User|null $routeUser */
        $routeUser = $this->route('user');
        $userId    = $routeUser?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'cpf'  => [
                'required',
                'string',
                new ValidCpf(),
                Rule::unique('users')->ignore($userId),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'job_position' => ['required', 'string', 'max:255'],
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
        if ($this->has('cpf') || $this->has('zip_code')) {
            $this->merge([
                'manager_id' => $this->user()?->id,
                'cpf'        => preg_replace('/\D/', '', (string) $this->input('cpf')),
                'zip_code'   => preg_replace('/\D/', '', (string) $this->input('zip_code')),
            ]);
        }
    }
}
