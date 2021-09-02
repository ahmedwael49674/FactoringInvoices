<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\InvoiceMarkedAsOpenEvent::class => [
            \App\Listeners\AddInvoiceToDebtorAmount::class,
        ],
        \App\Events\InvoiceMarkedAsPaidEvent::class => [
            \App\Listeners\RemoveInvoiceFromDebtorAmount::class,
        ],
    ];
}
