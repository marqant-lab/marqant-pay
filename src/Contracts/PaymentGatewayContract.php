<?php

namespace Marqant\MarqantPay\Contracts;

use Marqant\MarqantPay\Models\Payment;
use Illuminate\Database\Eloquent\Model;

abstract class PaymentGatewayContract
{
    /**
     * Create customer on the provider end and update the user.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public abstract function createCustomer(Model $Billable): Model;

    /**
     * Charge a given billable for a given amount.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     *
     * @param int                                 $amount
     *
     * @return \Marqant\MarqantPay\Models\Payment
     */
    public abstract function charge(Model $Billable, int $amount): Payment;

    /**
     * Subscribe a given Billable to a plan on the payment provider side.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     * @param \Illuminate\Database\Eloquent\Model $Plan
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public abstract function subscribe(Model $Billable, Model $Plan): Model;

    /**
     * Save the provided payment method to the given Billable on the payment provider side.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     * @param array                               $payment_method
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public abstract function savePaymentMethod(Model $Billable, array $payment_method): Model;

    /**
     * Remove the provided payment method from the given Billable on the payment provider side.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     * @param array                               $payment_method
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public abstract function removePaymentMethod(Model $Billable, array $payment_method): Model;
}