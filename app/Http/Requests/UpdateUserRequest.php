<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine se o usuário está autorizado a fazer esta solicitação.
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
     * Obtenha as regras de validação que se aplicam à solicitação.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var User|null $routeUser */
        $routeUser = $this->route('user');
        $userId = $routeUser?->id;
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'cpf'  => [
                'required',
                'string',
                'size:11',
                'regex:/^\d{11}$/',
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
            'zip_code'     => ['required', 'string', 'size:9', 'regex:/^\d{5}-\d{3}$/'],
            'street'       => ['required', 'string', 'max:255'],
            'number'       => ['nullable', 'string', 'max:20'],
            'complement'   => ['nullable', 'string', 'max:255'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'city'         => ['required', 'string', 'max:255'],
            'state'        => ['required', 'string', 'size:2'],
        ];
    }
}
