<?php

namespace Marqant\MarqantPay\Providers;

use Marqant\MarqantPay\Events\InvoiceSaved;
use Marqant\MarqantPay\Listeners\SendInvoice;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class MarqantPayEventsServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        InvoiceSaved::class => [
            SendInvoice::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}