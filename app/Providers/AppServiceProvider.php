<?php

namespace App\Providers;

use App\Models\Ticket;
use Illuminate\Support\Facades\Gate;
use App\Policies\Api\V1\TicketPolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Ticket::class, TicketPolicy::class);
    }
}
