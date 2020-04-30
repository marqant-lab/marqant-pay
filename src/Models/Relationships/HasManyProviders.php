<?php

namespace Marqant\MarqantPay\Models\Relationships;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin \Eloquent
 */
trait HasManyProviders
{
    /**
     * Establishes a belongs to many relationship with the Provider model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany;
     */
    public function providers(): HasMany
    {
        $model = config('marqant-pay.payment_model');

        return $this->hasMany($model);
    }
}