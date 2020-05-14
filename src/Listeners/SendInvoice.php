<?php

namespace Marqant\MarqantPay\Listeners;

use Mail;
use Marqant\MarqantPay\Mail\Invoice;
use Marqant\MarqantPay\Events\InvoiceSaved;

class SendInvoice
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \Marqant\MarqantPay\Events\InvoiceSaved $event
     *
     * @return void
     */
    public function handle(InvoiceSaved $event)
    {
        // prevent from proceeding when status is not succeeded
        if ($event->invoice->status !== 'succeeded') {
            return;
        }

        // check if user has already been notified
        if ($event->invoice->invoice_sent) {
            return;
        }

        // check if the pdf is available already
        if (!$event->invoice->invoice) {
            return;
        }

        // send out email to billable
        Mail::to($event->invoice->billable)
            ->send(new Invoice($event->invoice));

        $event->invoice->update([
            'invoice_sent' => 1,
        ]);
    }
}