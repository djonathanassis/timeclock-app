<?php

declare(strict_types = 1);

namespace App\Providers;

use App\Models\TimeEntry;
use App\Models\User;
use App\Policies\TimeEntryPolicy;
use App\Policies\UserPolicy;
use App\Repositories\TimeEntry\TimeEntryRepository;
use App\Repositories\TimeEntry\TimeEntryRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\TimeEntry\Interfaces\TimeEntryServiceInterface;
use App\Services\TimeEntry\TimeEntryService;
use App\Services\User\Interfaces\UserServiceInterface;
use App\Services\User\UserService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    #[\Override]
    public function register(): void
    {
        $this->app->bind(TimeEntryRepositoryInterface::class, TimeEntryRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TimeEntryServiceInterface::class, TimeEntryService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(TimeEntry::class, TimeEntryPolicy::class);

        $this->configureDates();
    }

    /**
     * @return void
     */
    private function configureDates(): void
    {
        Date::use(Carbon::class);
    }
}
