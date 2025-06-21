<?php

declare(strict_types = 1);

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use DateTime;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property string $name
 * @property string $cpf
 * @property string $email
 * @property string $password
 * @property string $job_position
 * @property DateTime $birth_date
 * @property string $zip_code
 * @property string $street
 * @property string|null $number
 * @property string|null $complement
 * @property string $neighborhood
 * @property string $city
 * @property string $state
 * @property string $role
 * @property int|null $manager_id
 * @property User|null $manager
 * @property Collection<int, User> $employees
 * @property Collection<int, TimeEntry> $timeEntries
 *
 * @method static UserFactory factory(...$parameters)
 */
class User extends Authenticatable implements MustVerifyEmail
{
    /** @phpstan-use HasFactory<UserFactory> */
    use HasFactory;
    use Notifiable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'cpf',
        'email',
        'password',
        'job_position',
        'birth_date',
        'zip_code',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'role',
        'manager_id',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'birth_date'        => 'date',
            'role'              => UserRole::class,
        ];
    }

    /**
     * @return BelongsTo<User, User>
     */
    public function manager(): BelongsTo
    {
        /** @var BelongsTo<User, User> */
        return $this->belongsTo(self::class, 'manager_id');
    }

    /**
     * @return HasMany<User, User>
     */
    public function employees(): HasMany
    {
        /** @var HasMany<User, User> */
        return $this->hasMany(self::class, 'manager_id');
    }

    /**
     * @return HasMany<TimeEntry, User>
     */
    public function timeEntries(): HasMany
    {
        /** @var HasMany<TimeEntry, User> */
        return $this->hasMany(TimeEntry::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN->value;
    }

    public function isEmployee(): bool
    {
        return $this->role === UserRole::EMPLOYEE->value;
    }
}
