<?php

namespace Marqant\MarqantPay\Tests\Services;

use Marqant\MarqantPay\Services\MarqantPay;
use Marqant\MarqantPay\Tests\MarqantPayTestCase;
use Marqant\MarqantPay\Contracts\PaymentGatewayContract;

/**
 * Class MarqantPayServiceTest
 *
 * @package Marqant\MarqantPay\Tests
 */
class MarqantPayTest extends MarqantPayTestCase
{
    /**
     * Test if we can resolve the gateway provider from a billable.
     *
     * @test
     *
     * @return void
     * @throws \Exception
     */
    public function test_if_we_can_resolve_the_provider_from_billable()
    {
        $User = $this->createCustomer();

        $Gateway = MarqantPay::resolveProviderGateway($User);

        $this->assertInstanceOf(PaymentGatewayContract::class, $Gateway);
    }

    /**
     * Test that we can create a user from the billable.
     *
     * @test
     *
     * @return void
     */
    public function test_create_customer_from_billable()
    {
        $provider = 'stripe';

        // create fake customer through factory
        $User = $this->createCustomer();

        // create customer on provider side
        $User->createCustomer($provider);

        // assert that we have a provider set on the billable
        $this->assertEquals($provider, $User->marqant_pay_provider);

        // assert that we have a stripe id
        $this->assertNotEmpty($User->stripe_id);
    }

    /**
     * Test if we can save a payment method to the user.
     *
     * @test
     *
     * @return void
     *
     * @throws \Exception
     */
    public function test_saving_payment_method_on_billable(): void
    {
        /**
         * @var \App\User $User
         */

        // set provider string
        $provider = 'stripe';

        // create fake customer through factory
        $User = $this->createCustomer();

        // create customer on provider side
        $User->createCustomer($provider);

        // create sample payment method
        $PaymentMethod = $this->createPaymentMethod();

        // save payment method to billable
        $User->savePaymentMethod($PaymentMethod);

        // assert that we have a payment method on the user
        $this->assertTrue($User->hasPaymentMethod());

        // assert that the payment method belongs to the user
        $this->assertEquals($User->stripe_id, $PaymentMethod->object->customer);

        // assert that the brand matches
        $this->assertEquals($User->marqant_pay_card_brand, $PaymentMethod->object->card->brand);

        // assert that the last four digits saved match
        $this->assertEquals($User->marqant_pay_card_last_four, $PaymentMethod->object->card->last4);

        // assert that we can get the payment method back from the billable
        $this->assertInstanceOf(config('marqant-pay.payment_methods.card'), $User->getPaymentMethod());
    }

    /**
     * Test if we can charge a billable.
     *
     * @test
     *
     * @return void
     */
    public function test_billable_can_be_charged()
    {
        $amount = 999; // 9,99 ($|€|...)

        $provider = 'stripe';

        // create fake customer through factory
        $User = $this->createCustomer();

        // create customer on provider side
        $User->createCustomer($provider);

        // charge the user
        $response = $User->charge($amount);

        // check that we got back an instance of Payment
        $this->assertInstanceOf(Payment::class, $response);

        // check the amount
        $this->assertEquals($amount, $response->amount);

        // check if we billed the correct user
        $this->assertEquals($User->provider_id, $response->customer);
    }
}
