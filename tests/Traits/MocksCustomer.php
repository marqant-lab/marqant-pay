<?php

namespace Marqant\MarqantPay\Tests\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait MocksCustomer
 *
 * This trait will help you with your tests to quickly get a customer object through the factory helper.
 */
trait MocksCustomer
{
    /**
     * @param array $options
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function createUser(array $options = []): Model
    {
        $default_data = [];

        return factory(\App\User::class)->create(array_merge($default_data, $options));
    }

    /**
     * @param array $options
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Exception
     */
    protected function createBillableUser(array $options = []): Model
    {
        /**
         * @var \Marqant\MarqantPay\Tests\Services\MarqantPayTest $this
         * @var \App\User                                         $Billable
         */

        $provider = 'stripe';

        // create empty customer
        $Billable = $this->createUser();

        // create customer on provider side
        $Billable->createCustomer($provider);

        // create sample payment method
        $PaymentMethod = $this->createPaymentMethod();

        $Billable->savePaymentMethod($PaymentMethod);

        return $Billable;
    }
}