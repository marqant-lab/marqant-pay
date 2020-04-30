<?php

namespace Marqant\MarqantPay\Models\Relationships;

use Illuminate\Database\Eloquent\Relations\MorphTo;

trait BelongsToManyBillables
{
    /**
     * Establish relationship to any billables.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function billable(): MorphTo
    {
        return $this->morphTo();
    }
}