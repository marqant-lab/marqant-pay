<?php

namespace Marqant\MarqantPay\Contracts;

use Illuminate\Database\Eloquent\Model;

abstract class ProviderInvoiceServiceContract
{
    /**
     * Create an invoice from the given payment
     *
     * @param \Illuminate\Database\Eloquent\Model $Payment
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    abstract protected function createInvoice(Model $Payment): Model;

    /**
     * Send invoice to billable email.
     *
     * @param \Illuminate\Database\Eloquent\Model $Payment
     *
     * @return bool
     */
    abstract protected function sendInvoice(Model $Payment): bool;
}