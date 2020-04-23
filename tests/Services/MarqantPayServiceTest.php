<?php

namespace Marqant\MarqantPay\Tests\Services;

use Marqant\MarqantPay\Tests\MarqantPayTestCase;
use Marqant\MarqantPay\Services\MarqantPayService;
use Marqant\MarqantPay\Contracts\PaymentGatewayContract;

/**
 * Class MarqantPayServiceTest
 *
 * @package Marqant\MarqantPay\Tests
 */
class MarqantPayServiceTest extends MarqantPayTestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_if_we_can_resolve_the_provider_from_billable()
    {
        $User = $this->createCustomer();

        $Gateway = MarqantPayService::resolveProviderGateway($User);

        $this->assertInstanceOf(PaymentGatewayContract::class, $Gateway);
    }
}
