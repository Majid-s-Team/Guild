<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\FeedBack;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('moderate-comment', function ($user) {
            return $user->hasPermissionTo('moderate comment')
                ? true
                : throw new AccessDeniedHttpException;
        });

        Gate::define('delete-feedback', function ($user) {
            return $user->hasRole('admin')
                ? true
                : throw new AccessDeniedHttpException;
        });

    }
}
