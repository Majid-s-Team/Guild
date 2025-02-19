<?php

namespace App\Providers;

use App\Contracts\LogServiceInterface;
use App\Models\FeedBack;
use App\Models\User;
use App\Observers\FeedbackObserver;
use App\Observers\UserObserver;
use App\Services\LogService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //$this->app->bind(LogServiceInterface::class,LogService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //User::observe(UserObserver::class);
        //FeedBack::observe(FeedbackObserver::class);


    }
}
