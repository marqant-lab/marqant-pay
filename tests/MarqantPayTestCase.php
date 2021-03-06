<?php

namespace Marqant\MarqantPay\Tests;

use Tests\CreatesApplication;
use Marqant\MarqantPay\Tests\Traits\MocksPlans;
use Marqant\MarqantPay\Tests\Traits\MocksCustomer;
use Marqant\MarqantPay\Tests\Traits\MocksPaymentMethods;
use Marqant\MarqantPay\Tests\Traits\MigrateFreshSeedOnce;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class MarqantPayTestCase extends BaseTestCase
{
    use CreatesApplication;
    use MocksCustomer;
    // use RefreshDatabase;
    use MigrateFreshSeedOnce;
    use MocksPaymentMethods;
    use MocksPlans;
}
