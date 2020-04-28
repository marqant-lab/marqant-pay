<?php

namespace Marqant\MarqantPay\Models\Relationships;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Trait HasManyPayments
 *
 * @package Marqant\MarqantPay\Models\Relationships
 *
 * @mixin \Eloquent
 */
trait HasManyPayments
{
    /**
     * Establishes a belongs to many relationship with the Payment model.
     *
     * @return HasMany;
     */
    public function payments(): HasMany
    {
        $model = config('marqant-pay.payment_model');

        return $this->hasMany($model);
    }
}
