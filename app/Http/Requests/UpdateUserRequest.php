<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\ValidCpf;
use App\Rules\ValidZipCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        $user = $this->user();

        if ($user === null) {
            return false;
        }

        return $user->can('admin');
    }

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
            'password'     => ['nullable', 'string', 'min:8', 'confirmed'],
            'job_position' => ['required', 'string', 'max:255'],
            'birth_date'   => ['required', 'date', 'before:today'],
            'zip_code'     => ['required', 'string', new ValidZipCode()],
            'street'       => ['required', 'string', 'max:255'],
            'number'       => ['nullable', 'string', 'max:20'],
            'complement'   => ['nullable', 'string', 'max:255'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'city'         => ['required', 'string', 'max:255'],
            'state'        => ['required', 'string', 'size:2'],
        ];
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('cpf') || $this->has('zip_code')) {
            $this->merge([
                'cpf' => preg_replace('/\D/', '', (string) $this->input('cpf')),
                'zip_code' => preg_replace('/\D/', '', (string) $this->input('zip_code')),
            ]);
        }
    }
}
