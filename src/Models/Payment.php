<?php

namespace Marqant\MarqantPay\Models;

use Illuminate\Database\Eloquent\Model;
use Marqant\MarqantPay\Events\InvoiceSaved;
use Marqant\MarqantPay\Traits\RepresentsInvoice;
use Marqant\MarqantPay\Traits\RepresentsPayment;

/**
 * Class Payment
 *
 * @mixin \Eloquent
 */
class Payment extends Model
{
    use RepresentsPayment;
    use RepresentsInvoice;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The event map for the invoices.
     *
     * We need to fire an event that let's you handle the state of a invoice. Eventually we also need to send out
     * emails when a payment and invoice are ready.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saved' => InvoiceSaved::class,
    ];
}
