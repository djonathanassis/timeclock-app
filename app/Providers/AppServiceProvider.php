<?php

declare(strict_types = 1);

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\TimeEntry;
use App\Policies\UserPolicy;
use App\Policies\TimeEntryPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(TimeEntry::class, TimeEntryPolicy::class);

        $this->configGates();
    }

    /**
     * @return void
     */
    private function configGates(): void
    {
        foreach (UserRole::cases() as $role) {
            Gate::define($role->value, static fn (User $user): bool => $user->role === $role);
        }
    }
}
