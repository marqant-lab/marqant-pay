<?php

namespace Marqant\MarqantPay\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class InvoiceSaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The invoice that triggers this event.
     *
     * @param \Illuminate\Database\Eloquent\Model $invoice
     */
    public $invoice;

    /**
     * Create a new event instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $Invoice
     */
    public function __construct(Model $Invoice)
    {
        $this->invoice = $Invoice;
    }
}
