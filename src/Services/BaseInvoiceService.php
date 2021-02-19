<?php

namespace Marqant\MarqantPay\Services;

use Illuminate\Database\Eloquent\Model;
use Marqant\MarqantPay\Contracts\ProviderInvoiceServiceContract;

abstract class BaseInvoiceService extends ProviderInvoiceServiceContract
{
    public function sendInvoice(Model $Payment): bool
    {
        // TODO: Implement sendInvoice() method.
    }
}
