<?php

namespace App\Providers;

use App\Models\Company\Employee;
use App\Models\Rating\MatrixTemplate;
use App\Models\Rating\Rating;
use App\Models\Statistic\Client;
use App\Models\Statistic\Marker;
use App\Observers\Company\EmployeeObserver;
use App\Observers\Rating\MatrixTemplateObserver;
use App\Observers\Rating\RatingObserver;
use App\Observers\Statistic\ClientObserver;
use App\Observers\Statistic\MarkerObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Employee::observe(EmployeeObserver::class);
        Rating::observe(RatingObserver::class);
        MatrixTemplate::observe(MatrixTemplateObserver::class);
        Client::observe(ClientObserver::class);
        Marker::observe(MarkerObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
