<?php

namespace Marqant\MarqantPay\Contracts;

use Illuminate\Database\Eloquent\Model;
use Marqant\MarqantPay\Services\MarqantPay;

abstract class PaymentGatewayContract
{
    /**
     * Create customer on the provider end and update the user.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public abstract function createCustomer(Model &$Billable): Model;

    /**
     * Charge a given billable for a given amount.
     *
     * @param \Illuminate\Database\Eloquent\Model                      $Billable
     * @param int                                                      $amount
     * @param null|\Marqant\MarqantPay\Contracts\PaymentMethodContract $PaymentMethod
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public abstract function charge(Model $Billable, int $amount, ?PaymentMethodContract $PaymentMethod = null): Model;

    /**
     * Update Payment status through received payment provider
     *
     * @param \Illuminate\Database\Eloquent\Model $Payment
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public abstract function updatePaymentStatus(Model $Payment): Model;

    /**
     * Get or create (if not exists) Payment using provider data
     *
     * @param array $invoice_data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public abstract function getPaymentByInvoice(array $invoice_data): Model;

    /**
     * Create Payment using provider data
     *
     * @param string $paymentID
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public abstract function createPaymentByProviderPaymentID(string $paymentID): Model;

    /**
     * Subscribe a given Billable to a plan on the payment provider side.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     * @param \Illuminate\Database\Eloquent\Model $Plan
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public abstract function subscribe(Model &$Billable, Model $Plan): Model;

    /**
     * Save the provided payment method to the given Billable on the payment provider side.
     *
     * @param \Illuminate\Database\Eloquent\Model                 $Billable
     * @param \Marqant\MarqantPay\Contracts\PaymentMethodContract $PaymentMethod
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public abstract function savePaymentMethod(Model &$Billable, PaymentMethodContract $PaymentMethod): Model;

    /**
     * Remove the provided payment method from the given Billable on the payment provider side.
     *
     * @param \Illuminate\Database\Eloquent\Model                 $Billable
     * @param \Marqant\MarqantPay\Contracts\PaymentMethodContract $PaymentMethod
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public abstract function removePaymentMethod(Model &$Billable, PaymentMethodContract $PaymentMethod): Model;

    /**
     * Check if billable has a payment method attached.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     *
     * @return bool
     */
    public abstract function hasPaymentMethod(Model $Billable): bool;

    /**
     * Check if billable has a payment method attached.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     *
     * @return \Marqant\MarqantPay\Contracts\PaymentMethodContract
     */
    public abstract function getPaymentMethodOfBillable(Model $Billable): PaymentMethodContract;

    /**
     * Method to get the currency to use. Feel free to overwrite depending on the provider implementation.
     */
    public function getCurrency(): string
    {
        return MarqantPay::getCurrency();
    }
}
