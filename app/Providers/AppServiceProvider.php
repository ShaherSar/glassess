<?php

namespace App\Providers;

use App\Models\Frame;
use App\Policies\CurrencyPolicy;
use App\Policies\FramePolicy;
use App\Policies\LensPolicy;
use App\Models\Lens;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\Currency;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Currency::class, CurrencyPolicy::class);
        Gate::policy(Frame::class, FramePolicy::class);
        Gate::policy(Lens::class, LensPolicy::class);
    }
}
