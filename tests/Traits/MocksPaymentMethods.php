<?php

namespace Marqant\MarqantPay\Tests\Traits;

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
            'token' => 'pm_card_visa',
        ];

        return MarqantPay::resolvePaymentMethod($type, $details);
    }
}