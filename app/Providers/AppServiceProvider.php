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
use App\Services\Address\AddressService;
use App\Services\Address\AddressServiceInterface;
use App\Services\Address\Providers\AddressProviderInterface;
use App\Services\Address\Providers\ViaCepProvider;
use App\Services\TimeEntry\Interfaces\TimeEntryServiceInterface;
use App\Services\TimeEntry\TimeEntryService;
use App\Services\User\Interfaces\UserServiceInterface;
use App\Services\User\UserService;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        // Repositórios
        $this->app->bind(TimeEntryRepositoryInterface::class, TimeEntryRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        
        // Serviço de endereço e seu provider
        $this->app->bind(AddressProviderInterface::class, function ($app) {
            return new ViaCepProvider(
                offlineMode: config('address.offline_mode', false),
                acceptValidFormatOnFailure: config('address.accept_valid_format_on_failure', true)
            );
        });
        $this->app->bind(AddressServiceInterface::class, AddressService::class);
        
        // Serviços de negócio
        $this->app->bind(TimeEntryServiceInterface::class, TimeEntryService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(TimeEntry::class, TimeEntryPolicy::class);

        $this->configureDates();
    }

    /**
     * Configure the application's dates.
     */
    private function configureDates(): void
    {
        Date::use(CarbonImmutable::class);
    }
}
