<?php

namespace Marqant\MarqantPay\Contracts;

use Illuminate\Database\Eloquent\Model;

abstract class PaymentGatewayContract
{
    /**
     * Subscribe a given Billable to a plan on the payment provider side.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     * @param \Illuminate\Database\Eloquent\Model $Plan
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected abstract function subscribe(Model $Billable, Model $Plan): Model;

    /**
     * Save the provided payment method to the given Billable on the payment provider side.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     * @param array                               $payment_method
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected abstract function savePaymentMethod(Model $Billable, array $payment_method): Model;

    /**
     * Remove the provided payment method from the given Billable on the payment provider side.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     * @param array                               $payment_method
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected abstract function removePaymentMethod(Model $Billable, array $payment_method): Model;

    /**
     * Charge a given billable for a given amount.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     * @param array                               $payment_method
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected abstract function charge(Model $Billable, array $payment_method): Model;
}