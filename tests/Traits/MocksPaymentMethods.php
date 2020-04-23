<?php

namespace Marqant\MarqantPay\Tests\Traits;

use Illuminate\Support\Carbon;
use Marqant\MarqantPay\Services\MarqantPay;
use Marqant\MarqantPay\Contracts\PaymentMethodContract;

/**
 * Trait MocksCustomer
 *
 * This trait will help you with your tests to quickly get a customer object through the factory helper.
 */
trait MocksPaymentMethods
{
    /**
     * @param array $options
     *
     * @return \Marqant\MarqantPay\Contracts\PaymentMethodContract
     *
     * @throws \Exception
     */
    protected function createPaymentMethod(array $options = []): PaymentMethodContract
    {
        $type = 'card';

        $details = [
            'type' => 'card',
            'card' => [
                'number'    => '4242424242424242',
                'exp_month' => 4,
                'exp_year'  => Carbon::now()
                    ->addYear()
                    ->format('Y'),
                'cvc'       => '314',
            ],
        ];

        return MarqantPay::resolvePaymentMethod($type, $details);
    }
}