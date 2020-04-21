<?php

namespace Marqant\MarqantPay\Models\Relationships;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin \Eloquent
 */
trait BelongsToManyProviders
{
    /**
     * Establishes a belongs to many relationship with the Provider model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany;
     */
    public function providers(): BelongsToMany
    {
        $model = config('marqant-pay.provider_model');

        return $this->belongsToMany($model);
    }
}