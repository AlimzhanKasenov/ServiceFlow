<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class EventServiceProvider
 *
 * Регистрирует события и слушателей системы ServiceFlow.
 *
 * Здесь мы связываем:
 * - события (Events)
 * - обработчики (Listeners)
 *
 * Это основа event-driven архитектуры:
 * → любое действие в системе может запускать цепочку реакций
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * Карта событий → слушатели
     */
    protected $listen = [

        /**
         * Событие смены стадии заявки
         */
        \App\Events\RequestStageChanged::class => [

            /**
             * Автоматизации (роботы / бизнес-логика)
             */
            \App\Listeners\HandleAutomationAfterStageChange::class,

            /**
             * Webhooks / интеграции
             */
            \App\Listeners\HandleWebhookAfterStageChange::class,

        ],

    ];

    /**
     * Регистрация событий (если понадобится)
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Автоматическое обнаружение событий (можно включить позже)
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
