<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Event::listen('books.averageStar', 'App\Events\AverageStarBookHandler');
        Event::listen('book.deleted', 'App\Events\DeleteBookHandler');
        Event::listen('notification', 'App\Events\SaveNotificationHandler');
        Event::listen('count_notification', 'App\Events\NotificationHandler');
        Event::listen('androidNotification', 'App\Events\NotificationAndroidHander');
    }
}
