<?php

namespace Marqant\MarqantPay\Contracts;

abstract class PaymentMethodContract
{
    /**
     * @var string The type of this payment.
     */
    protected string $type;

    /**
     * Method to generate the given payment method from details.
     *
     * @param array $details
     *
     * @return \Marqant\MarqantPay\Contracts\PaymentMethodContract
     */
    public abstract static function make(array $details): PaymentMethodContract;
}