<?php

namespace Marqant\MarqantPay\Tests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Marqant\MarqantPay\Services\MarqantPayService;
use Marqant\MarqantPay\Contracts\PaymentGatewayContract;

/**
 * Class MarqantPayServiceTest
 *
 * @package Marqant\MarqantPay\Tests
 */
class MarqantPayServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_if_we_can_resolve_a_the_provider()
    {
        $User = factory(\App\User::class)->make([
            'marqant_pay_provider' => 'stripe',
        ]);

        $Gateway = MarqantPayService::resolveProviderGateway($User);

        $this->assertInstanceOf(PaymentGatewayContract::class, $Gateway);
    }
}
