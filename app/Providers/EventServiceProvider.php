<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Events\RequestStageChanged;

use App\Listeners\HandleAutomationAfterStageChange;
use App\Listeners\HandleWebhookAfterStageChange;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [

        RequestStageChanged::class => [

            HandleAutomationAfterStageChange::class,

            HandleWebhookAfterStageChange::class,

        ],

    ];
}
