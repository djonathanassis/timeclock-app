<?php

declare(strict_types = 1);

namespace App\Services\User\DTOs;

use Illuminate\Support\Facades\Hash;

readonly class UserDTO
{
    public function __construct(
        public string $name,
        public string $cpf,
        public string $email,
        public ?string $password,
        public string$jobPosition,
        public string $birthDate,
        public string $zipCode,
        public string $street,
        public string $number,
        public string $complement,
        public string $neighborhood,
        public string $city,
        public string $state,
        public ?int $managerId,
        public string $role
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? '',
            cpf: $data['cpf'] ?? '',
            email: $data['email'] ?? '',
            password: $data['password'] ?? null,
            jobPosition: $data['job_position'],
            birthDate: $data['birth_date'] ?? '',
            zipCode: $data['zip_code'] ?? '',
            street: $data['street'] ?? '',
            number: $data['number'] ?? '',
            complement: $data['complement'] ?? '',
            neighborhood: $data['neighborhood'] ?? '',
            city: $data['city'] ?? '',
            state: $data['state'] ?? '',
            managerId: $data['manager_id'] ?? null,
            role: $data['role'] ?? 'employee'
        );
    }

    public function toArray(): array
    {
        $data = [
            'name'         => $this->name,
            'cpf'          => $this->cpf,
            'email'        => $this->email,
            'job_position' => $this->jobPosition,
            'birth_date'   => $this->birthDate,
            'zip_code'     => $this->zipCode,
            'street'       => $this->street,
            'number'       => $this->number,
            'complement'   => $this->complement,
            'neighborhood' => $this->neighborhood,
            'city'         => $this->city,
            'state'        => $this->state,
            'manager_id'   => $this->managerId,
            'role'         => $this->role,
        ];

        if ($this->password !== null) {
            $data['password'] = Hash::make($this->password);
        }

        return $data;
    }
}
