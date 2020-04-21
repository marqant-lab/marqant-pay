<?php

namespace Marqant\MarqantPay\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Marqant\MarqantPay\Services\MarqantPayService;

trait Billable
{
    /**
     * Subscribe billable model to a plan.
     *
     * @param string $plan
     *
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function subscribe(string $plan): Model
    {
        MarqantPayService::subscribe($this, $plan);

        return $this;
    }

    /**
     * Save payment method to billable model.
     *
     * @param array $payment_method
     *
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
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
     * Charge the Billable for a given amount.
     *
     * @param int $amount
     *
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    private function charge(array $payment_method): Model
    {
        MarqantPayService::charge($this, $payment_method);

        return $this;
    }

    /**
     * Create customer at the payment provider.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @param string                                $provider_id
     *
     * @return \Illuminate\Database\Eloquent\Builder $query
     */
    private function scopeByProviderId(Builder $query, string $provider_id): Builder
    {
        return $query->where('provider_id', $provider_id);
    }
}