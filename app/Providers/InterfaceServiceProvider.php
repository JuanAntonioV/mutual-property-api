<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class InterfaceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // ------------------ Registering Services ------------------
        // ----------------------------------------------------------
        $this->app->bind(
            \App\Services\Auth\AuthServiceInterface::class,
            \App\Services\Auth\AuthService::class
        );

        $this->app->bind(
            \App\Services\User\UserServiceInterface::class,
            \App\Services\User\UserService::class
        );

        $this->app->bind(
            \App\Services\Product\ProductServiceInterface::class,
            \App\Services\Product\ProductService::class
        );

        $this->app->bind(
            \App\Services\Admin\Auth\AdminAuthServiceInterface::class,
            \App\Services\Admin\Auth\AdminAuthService::class
        );

        $this->app->bind(
            \App\Services\Admin\AdminServiceInterface::class,
            \App\Services\Admin\AdminService::class
        );

        $this->app->bind(
            \App\Services\Admin\Product\AdminProductServiceInterface::class,
            \App\Services\Admin\Product\AdminProductService::class
        );

        // ------------------ Registering Repository ------------------
        // ------------------------------------------------------------

        $this->app->bind(
            \App\Repository\Auth\AuthRepoInterface::class,
            \App\Repository\Auth\AuthRepo::class
        );

        $this->app->bind(
            \App\Repository\User\UserRepoInterface::class,
            \App\Repository\User\UserRepo::class
        );

        $this->app->bind(
            \App\Repository\AccessLog\AccessLogRepoInterface::class,
            \App\Repository\AccessLog\AccessLogRepo::class,
        );

        $this->app->bind(
            \App\Repository\PasswordReset\PasswordResetRepoInterface::class,
            \App\Repository\PasswordReset\PasswordResetRepo::class,
        );

        $this->app->bind(
            \App\Repository\Product\ProductRepoInterface::class,
            \App\Repository\Product\ProductRepo::class,
        );

        $this->app->bind(
            \App\Repository\Admin\Auth\AdminAuthRepoInterface::class,
            \App\Repository\Admin\Auth\AdminAuthRepo::class,
        );
    }
}
