<?php

namespace App\Providers;

use mmghv\LumenRouteBinding\RouteBindingServiceProvider as BaseServiceProvider;

class RouteBindingServiceProvider extends BaseServiceProvider
{
    /**
     * Boot the service provider
     */
    public function boot()
    {
        // The binder instance
        $binder = $this->binder;

        // Here we define our bindings
        $binder->bind('channel', 'App\Models\Channel');
        $binder->bind('thread', 'App\Models\Thread');
        $binder->bind('reply', 'App\Models\Reply');
        $binder->bind('user', 'App\Models\User');
    }
}