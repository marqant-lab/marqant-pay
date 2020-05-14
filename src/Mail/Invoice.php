<?php

namespace Marqant\MarqantPay\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public Model $invoice;

    /**
     * Create a new message instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $Invoice
     */
    public function __construct(Model $Invoice)
    {
        $this->invoice = $Invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = config('marqant-pay.invoice_email_view');

        return $this->view($view)
            ->with([
                'invoice' => $this->invoice,
            ]);
    }
}
