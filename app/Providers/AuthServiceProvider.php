<?php

namespace App\Providers;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // Map your models to policies here
        // Example: 'App\Models\Post' => 'App\Policies\PostPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('update-event', function($user, Event $event) {
            return $user->id === $event->user_id;

        
    });

        Gate::define('delete-attendee', function($user, Event $event, Attendee $attendee) {
            return $user->id === $event->user_id ||
            $user->id === $attendee->user_id;
        });
    }
}