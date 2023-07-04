<?php

namespace App\Providers;

use App\Models\Company\Employee;
use App\Models\Rating\MatrixTemplate;
use App\Models\Rating\Rating;
use App\Models\Rating\ResultClient;
use App\Observers\Company\EmployeeObserver;
use App\Observers\Rating\MatrixTemplateObserver;
use App\Observers\Rating\RatingObserver;
use App\Observers\Rating\ResultClientObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
        ResultClient::observe(ResultClientObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
