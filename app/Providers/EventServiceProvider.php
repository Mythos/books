<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Series;
use App\Models\Volume;
use App\Observers\CategoryObserver;
use App\Observers\SeriesObserver;
use App\Observers\VolumeObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Category::observe(CategoryObserver::class);
        Series::observe(SeriesObserver::class);
        Volume::observe(VolumeObserver::class);
    }
}
