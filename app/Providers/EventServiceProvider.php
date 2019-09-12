<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
        \App\Events\UserCreatedEvent::class => [
            \App\Listeners\UserCreatedListener::class,
        ],
        \App\Events\StreamCreatedEvent::class => [
            \App\Listeners\StreamCreatedListener::class,
        ],
        \App\Events\StreamUpdatedEvent::class => [
            \App\Listeners\StreamUpdatedListener::class
        ],
        \App\Events\TransactionCreatedEvent::class => [
            \App\Listeners\TransactionCreatedListener::class,
        ],
        \App\Events\TransactionUpdatedEvent::class => [
            \App\Listeners\TransactionUpdatedListener::class,
        ],
        \App\Events\TaskCreatedEvent::class => [
            \App\Listeners\TaskCreatedListener::class
        ],
        \App\Events\TaskUpdatedEvent::class => [
            \App\Listeners\TaskUpdatedListener::class
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            'SocialiteProviders\\Twitch\\TwitchExtendSocialite@handle',
            'SocialiteProviders\\YouTube\\YouTubeExtendSocialite@handle',
            'SocialiteProviders\\Steam\\SteamExtendSocialite@handle',
            'SocialiteProviders\\Discord\\DiscordExtendSocialite@handle',
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

        //
    }
}
