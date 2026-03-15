<?php


namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Events\RequestStageChanged;
use App\Listeners\HandleAutomationAfterStageChange;

class EventServiceProvider extends ServiceProvider
{
    /**
     * События и слушатели
     */
    protected $listen = [

        RequestStageChanged::class => [
            HandleAutomationAfterStageChange::class,
        ],

    ];
}
