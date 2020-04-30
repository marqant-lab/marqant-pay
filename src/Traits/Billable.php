<?php

namespace Marqant\MarqantPay\Traits;

use Illuminate\Database\Eloquent\Model;
use Marqant\MarqantPay\Services\MarqantPay;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Marqant\MarqantPay\Contracts\PaymentMethodContract;

trait Billable
{
    /**
     * Create a customer on the provider end.
     *
     * @param string $provider
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Exception
     */
    public function createCustomer(string $provider): Model
    {
        /** @var Model $this */
        return MarqantPay::createCustomer($this, $provider);
    }

    /**
     * Charge the Billable for a given amount.
     *
     * @param int                                                      $amount
     * @param null|\Marqant\MarqantPay\Contracts\PaymentMethodContract $PaymentMethod
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Exception
     */
    public function charge(int $amount, ?PaymentMethodContract $PaymentMethod = null): Model
    {
        return MarqantPay::charge($this, $amount, $PaymentMethod);
    }

    /**
     * Save payment method to billable model.
     *
     * @param \Marqant\MarqantPay\Contracts\PaymentMethodContract $PaymentMethod
     *
     * @return \Illuminate\Database\Eloquent\Model|$this
     * @throws \Exception
     */
    public function savePaymentMethod(PaymentMethodContract $PaymentMethod): Model
    {
        MarqantPay::savePaymentMethod($this, $PaymentMethod);

        return $this;
    }

    /**
     * Check if billable has a payment method attached.
     *
     * @return bool
     * @throws \Exception
     */
    public function hasPaymentMethod(): bool
    {
        return MarqantPay::hasPaymentMethod($this);
    }

    /**
     * Get the payment method of the billable.
     *
     * @return \Marqant\MarqantPay\Contracts\PaymentMethodContract
     * @throws \Exception
     */
    public function getPaymentMethod(): PaymentMethodContract
    {
        return MarqantPay::getPaymentMethodOfBillable($this);
    }

    /**
     * Remove payment method from billable model.
     *
     * @param \Marqant\MarqantPay\Contracts\PaymentMethodContract $PaymentMethod
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Exception
     */
    public function removePaymentMethod(PaymentMethodContract $PaymentMethod): Model
    {
        MarqantPay::removePaymentMethod($this, $PaymentMethod);

        return $this;
    }

    /**
     * Establish a relationship to the payments model from any billable.
     */
    public function payments(): MorphMany
    {
        $model = config('marqant-pay.payment_model');

        return $this->morphMany($model, 'billable');
    }
}