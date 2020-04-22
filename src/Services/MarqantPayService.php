<?php

namespace Marqant\MarqantPay\Services;

use Illuminate\Support\Traits\Macroable;

class MarqantPayService
{
    /**
     * We make use of the Macroable trait to make this service extendable. That way, other packages can bring in
     * functionality, without requiring much boilerplate.
     */
    use Macroable;
}