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
        $default_data = [
            'marqant_pay_provider' => 'stripe',
        ];

        return factory(\App\User::class)->create(array_merge($default_data, $options));
    }
}