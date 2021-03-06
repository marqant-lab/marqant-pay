<?php

namespace Marqant\MarqantPay\Traits;

use Marqant\MarqantPay\Traits\Attributes\AmountAttribute;
use Marqant\MarqantPay\Traits\Attributes\InvoiceAttribute;

/**
 * Trait RepresentsInvoice
 *
 * @package Marqant\MarqantPay\Traits\Attributes
 */
trait RepresentsInvoice
{
    use AmountAttribute;
    use InvoiceAttribute;
}
