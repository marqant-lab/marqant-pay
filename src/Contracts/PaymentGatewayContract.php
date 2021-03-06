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
    abstract public function createCustomer(Model $Billable): Model;

    /**
     * Charge a given billable for a given amount.
     *
     * @param  Model                      $Billable
     * @param  float                      $amount
     * @param  null|PaymentMethodContract $PaymentMethod
     * @param  null|string                $description
     * @return Model
     */
    abstract public function charge(Model $Billable, float $amount, ?PaymentMethodContract $PaymentMethod = null, ?string $description = null): Model;

    /**
     * Update Payment status through received payment provider
     *
     * @param \Illuminate\Database\Eloquent\Model $Payment
     * @param string|null                         $status
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    abstract public function updatePaymentStatus(Model $Payment, $status = null): Model;

    /**
     * Send email if Payment failed for received payment provider
     *
     * @param \Illuminate\Database\Eloquent\Model $Payment
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    abstract public function sendEmailFailedPayment(Model $Payment): Model;

    /**
     * Send email if Payment failed for received payment provider
     *
     * @param \Illuminate\Database\Eloquent\Model $Payment
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    abstract public function sendSupportEmailFailedPayment(Model $Payment): Model;

    /**
     * Get or create (if not exists) Payment using provider data
     *
     * @param array $invoice_data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    abstract public function getPaymentByInvoice(array $invoice_data): Model;

    /**
     * Create Payment using provider data
     *
     * @param string $payment_id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    abstract public function createPaymentByProviderPaymentID(string $payment_id): Model;

    /**
     * Subscribe a given Billable to a plan on the payment provider side.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     * @param \Illuminate\Database\Eloquent\Model $Plan
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    abstract public function subscribe(Model $Billable, Model $Plan): Model;

    /**
     * Save the provided payment method to the given Billable on the payment provider side.
     *
     * @param \Illuminate\Database\Eloquent\Model                 $Billable
     * @param \Marqant\MarqantPay\Contracts\PaymentMethodContract $PaymentMethod
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    abstract public function savePaymentMethod(Model $Billable, PaymentMethodContract $PaymentMethod): Model;

    /**
     * Remove the provided payment method from the given Billable on the payment provider side.
     *
     * @param \Illuminate\Database\Eloquent\Model                 $Billable
     * @param \Marqant\MarqantPay\Contracts\PaymentMethodContract $PaymentMethod
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    abstract public function removePaymentMethod(Model $Billable, PaymentMethodContract $PaymentMethod): Model;

    /**
     * Check if billable has a payment method attached.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     *
     * @return bool
     */
    abstract public function hasPaymentMethod(Model $Billable): bool;

    /**
     * Check if billable has a payment method attached.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     *
     * @return \Marqant\MarqantPay\Contracts\PaymentMethodContract
     */
    abstract public function getPaymentMethodOfBillable(Model $Billable): PaymentMethodContract;

    /**
     * Method to get the currency to use. Feel free to overwrite depending on the provider implementation.
     */
    public function getCurrency(): string
    {
        return MarqantPay::getCurrency();
    }
}
