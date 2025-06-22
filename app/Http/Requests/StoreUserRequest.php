<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use App\Enums\JobPosition;
use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreUserRequest extends FormRequest
{
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
        return [
            'name'         => ['required', 'string', 'max:255'],
            'cpf'          => ['required', 'string', 'size:11', 'unique:users', 'regex:/^\d{11}$/'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
            'job_position' => ['required', new Enum(JobPosition::class)],
            'role'         => ['required', new Enum(UserRole::class)],
            'birth_date'   => ['required', 'date', 'before:today'],
            'zip_code'     => ['required', 'string', 'size:9', 'regex:/^\d{5}-\d{3}$/'],
            'street'       => ['required', 'string', 'max:255'],
            'number'       => ['nullable', 'string', 'max:20'],
            'complement'   => ['nullable', 'string', 'max:255'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'city'         => ['required', 'string', 'max:255'],
            'state'        => ['required', 'string', 'size:2'],
            'manager_id'   => ['nullable', 'exists:users,id'],
        ];
    }
}
