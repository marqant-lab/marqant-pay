<?php

namespace Marqant\MarqantPay\Traits;

use Illuminate\Database\Eloquent\Model;
use Marqant\MarqantPay\Services\MarqantPay;

/**
 * Trait Invoiceable
 *
 * @package Marqant\MarqantPayInvoices\Traits
 *
 * @mixin \Marqant\MarqantPay\Models\Payment
 */
trait Invoiceable
{
    /**
     * Create a PDF invoice from the given payment.
     */
    public function createInvoice(): Model
    {
        return MarqantPay::createInvoice($this);
    }
}