<?php

namespace App\Providers;

use App\Services\AdminService;
use App\Services\AdminServiceInterface;
use App\Services\UserService;
use App\Services\UserServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(UserServiceInterface::class, UserService::class);

        $this->app->singleton(AdminServiceInterface::class, AdminService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
