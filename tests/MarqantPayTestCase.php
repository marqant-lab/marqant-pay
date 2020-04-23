<?php

namespace Marqant\MarqantPay\Tests;

use Tests\CreatesApplication;
use Marqant\MarqantPay\Tests\Traits\MocksCustomer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class MarqantPayTestCase extends BaseTestCase
{
    use CreatesApplication;
    use MocksCustomer;
    use RefreshDatabase;
}
