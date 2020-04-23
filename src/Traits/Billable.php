<?php

namespace Marqant\MarqantPay\Traits;

use Illuminate\Database\Eloquent\Model;
use Marqant\MarqantPay\Services\MarqantPayService;

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
        return MarqantPayService::createCustomer($this, $provider);
    }

    /**
     * Charge the Billable for a given amount.
     *
     * @param int $amount
     *
     * @return \Marqant\MarqantPay\Models\Payment
     *
     * @throws \Exception
     */
    private function charge(int $amount): Payment
    {
        return MarqantPayService::charge($this, $amount);
    }

    /**
     * Save payment method to billable model.
     *
     * @param array $payment_method
     *
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    // TODO: create object for payment method
    private function savePaymentMethod(array $payment_method): Model
    {
        MarqantPayService::savePaymentMethod($this, $payment_method);

        return $this;
    }

    /**
     * Remove payment method from billable model.
     *
     * @param array $payment_method
     *
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    private function removePaymentMethod(array $payment_method): Model
    {
        MarqantPayService::removePaymentMethod($this, $payment_method);

        return $this;
    }

    /**
     * Subscribe billable model to a plan.
     *
     * @param string $plan
     *
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function subscribe(string $plan): Model
    {
        if (method_exists(MarqantPayService::class, 'subscribe')) {
            MarqantPayService::subscribe($this, $plan);
        }

        return $this;
    }
}