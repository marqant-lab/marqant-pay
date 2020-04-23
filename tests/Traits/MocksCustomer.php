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
    protected function createCustomer(array $options = []): Model
    {
        $default_data = [];

        return factory(\App\User::class)->create(array_merge($default_data, $options));
    }

    /**
     * @param array $options
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function createAssignedCustomer(array $options = []): Model
    {
        $default_data = [
            'email'                => 'bauch.blanca@example.org',
            'marqant_pay_provider' => 'stripe',
            'stripe_id'            => 'cus_H9OBNVPV8VCLml',
        ];

        return factory(\App\User::class)->create(array_merge($default_data, $options));
    }
}