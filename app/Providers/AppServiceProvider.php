<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Stage;
use App\Observers\StageObserver;

/**
 * Class AppServiceProvider
 *
 * Основной сервис-провайдер приложения.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Регистрация сервисов
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap сервисов
     */
    public function boot(): void
    {
        Stage::observe(StageObserver::class);
    }
}
