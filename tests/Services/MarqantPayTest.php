<?php

namespace Marqant\MarqantPay\Tests\Services;

use Stripe\SetupIntent as StripeSetupIntent;
use Marqant\MarqantPay\Services\MarqantPay;
use Stripe\Subscription as StripeSubscription;
use Marqant\MarqantPay\Tests\MarqantPayTestCase;
use Marqant\MarqantPayStripe\StripePaymentGateway;
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
        /**
         * @var \App\User $User
         */

        $User = $this->createBillableUser();

        $Gateway = MarqantPay::resolveProviderGateway($User);

        $this->assertInstanceOf(PaymentGatewayContract::class, $Gateway);
    }

    /**
     * Test that we can create a user from the billable.
     *
     * @test
     *
     * @return void
     * @throws \Exception
     */
    public function test_create_customer_from_billable()
    {
        /**
         * @var \App\User $User
         */

        $provider = 'stripe';

        // create fake customer through factory
        $User = $this->createUser();

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
        $User = $this->createUser();

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
     * Test if we can remove payment method from a billable.
     *
     * @test
     *
     * @return void
     *
     * @throws \Exception
     */
    public function test_removing_payment_method_from_billable(): void
    {
        /**
         * @var \App\User $User
         */

        // set provider string
        $provider = 'stripe';

        // create fake customer through factory
        $User = $this->createUser();

        // create customer on provider side
        $User->createCustomer($provider);

        // create sample payment method
        $PaymentMethod = $this->createPaymentMethod();

        // save payment method to billable
        $User->savePaymentMethod($PaymentMethod);

        // assert that we have a payment method on the user
        $this->assertTrue($User->hasPaymentMethod());

        // remove the payment method
        $User->removePaymentMethod($PaymentMethod);

        // assert that we no longer have a payment method on the billable
        $this->assertFalse($User->hasPaymentMethod());

        // assert that there is no customer assigned to the payment method
        $this->assertNull($PaymentMethod->object->customer);
    }

    /**
     * Test if we can charge a billable.
     *
     * @test
     *
     * @return void
     *
     * @throws \Exception
     */
    public function test_billable_can_be_charged()
    {
        /**
         * @var \App\User $User
         */

        $amount = 999; // 9,99 ($|€|...)

        // create fake customer through factory
        $User = $this->createBillableUser();

        // charge the user
        $Payment = $User->charge($amount);

        // check that we got back an instance of Payment
        $this->assertInstanceOf(config('marqant-pay.payment_model'), $Payment);

        // check the amount
        $this->assertEquals($amount, $Payment->amount);

        // check if we billed the correct user
        $this->assertEquals($User->provider_id, $Payment->customer);
    }

    /**
     * Test if we can charge a billable that has no default payment method assigned.
     *
     * @test
     *
     * @return void
     *
     * @throws \Exception
     */
    public function test_billable_without_setup_can_be_charged()
    {
        /**
         * @var \App\User $User
         */

        $amount = 999; // 9,99 ($|€|...)

        // create fake customer through factory
        $User = $this->createUser();

        $PaymentMethod = $this->createPaymentMethod();

        // charge the user
        $Payment = $User->charge($amount, $PaymentMethod);

        // check that we got back an instance of Payment
        $this->assertInstanceOf(config('marqant-pay.payment_model'), $Payment);

        // check the amount
        $this->assertEquals($amount, $Payment->amount);

        // check if we billed the correct user
        $this->assertNull($Payment->customer);
    }

    /**
     * Test if we can create a plan on the provider from our plan model.
     *
     * @test
     *
     * @return void
     *
     * @throws \Exception
     */
    public function test_create_plan_from_plan_model(): void
    {
        /**
         * @var \Marqant\MarqantPaySubscriptions\Models\Plan $Plan ;
         */

        $provider = 'stripe';

        $Plan = $this->createPlanModel();

        $Plan->createPlan($provider);

        // assert that provider and plan are connected through a many to many relationship
        $this->assertInstanceOf(config('marqant-pay.provider_model'), $Plan->providers->first());

        // assert that the field on the plan are filled with valid data
        $this->assertNotEmpty($Plan->stripe_id);
        $this->assertNotEmpty($Plan->stripe_product);
    }

    /**
     * Test if we can subscribe a billable to a plan.
     *
     * @test
     *
     * @return void
     *
     * @throws \Exception
     */
    public function test_subscribe_billable_to_plan(): void
    {
        /**
         * @var \Marqant\MarqantPaySubscriptions\Models\Plan $Plan
         * @var \App\User                                    $Billable
         */

        $provider = 'stripe';

        $Plan = $this->createPlanModel();

        $Plan->createPlan($provider);

        // assert that provider and plan are connected through a many to many relationship
        $this->assertInstanceOf(config('marqant-pay.provider_model'), $Plan->providers->first());

        // assert that the field on the plan are filled with valid data
        $this->assertNotEmpty($Plan->stripe_id);
        $this->assertNotEmpty($Plan->stripe_product);

        // get billable
        $Billable = $this->createBillableUser();

        // subscribe billable to plan with given provider
        $Billable->subscribe($Plan->slug);

        // assert that billable is subscribed via stripe
        $this->assertCount(1, StripeSubscription::all([
            'customer' => $Billable->stripe_id,
            'plan'     => $Plan->stripe_id,
        ]));

        // assert that billable is subscribed in our database
        $this->assertCount(1, $Billable->subscriptions);

        // assert that all values needed are stored in the database and valid
        $Subscription = $Billable->subscriptions->first();
        $this->assertNotEmpty($Subscription->stripe_id);
        $this->assertEquals($Billable->id, $Subscription->billable_id);
        $this->assertEquals($Plan->id, $Subscription->plan_id);
    }

    /**
     * Test if we can create a setup intent for a customer.
     *
     * @test
     *
     * @return void
     *
     * @throws \Exception
     */
    public function test_create_setup_intend_for_customer(): void
    {
        /**
         * @var \App\User $User
         */

        // set provider string
        $provider = 'stripe';

        // create fake customer through factory
        $User = $this->createUser();

        // create customer on provider side
        $User->createCustomer($provider);

        // create a setup intent to receive a client secret
        // note: this is stripe specific (as far as I can tell), so we need to proceed
        //       with the stripe payment gateway
        $StripeSetupIntent = StripePaymentGateway::createSetupIntent($User);

        // assert that this is an instance of a stripe setup intent
        $this->assertInstanceOf(StripeSetupIntent::class, $StripeSetupIntent);

        // assert that we have a client secret
        $this->assertNotEmpty($StripeSetupIntent->client_secret);
    }

    /**
     * Test if we can charge a user that requires a next action to perform.
     *
     * Note: we only test this process untill the point, where we get a next action back.
     *
     * @test
     *
     * @return void
     *
     * @throws \Exception
     */
    // public function test_charge_user_with_additional_next_action(): void
    // {
    //     /**
    //      * @var \App\User $User
    //      */
    //
    //     $amount = 999; // 9,99 ($|€|...)
    //
    //     // create fake customer through factory
    //     $User = $this->createBillableUserWithAdditionalActionRequired();
    //
    //     // charge the user
    //     $Payment = $User->charge($amount);
    //
    //     // check that we got back an instance of Payment
    //     $this->assertInstanceOf(config('marqant-pay.payment_model'), $Payment);
    //
    //     // check the amount
    //     $this->assertEquals($amount, $Payment->amount);
    //
    //     // check if we billed the correct user
    //     $this->assertEquals($User->provider_id, $Payment->customer);
    // }
}
