<?php

namespace Marqant\MarqantPay\Models\Relationships;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \Eloquent
 */
trait BelongsToProviders
{
    /**
     * Establishes a belongs to many relationship with the Provider model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo;
     */
    public function providers(): BelongsTo
    {
        $model = config('marqant-pay.provider_model');

        return $this->belongsTo($model);
    }
}