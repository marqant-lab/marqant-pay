<?php

namespace Marqant\MarqantPay\Traits;

use Rocky\Eloquent\HasDynamicRelation;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Marqant\MarqantPay\Models\Relationships\BelongsToProvider;

trait RepresentsPayment
{
    use BelongsToProvider;
    use HasDynamicRelation;

    /**
     * Establish relationship to all billables.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function billable(): MorphTo
    {
        return $this->morphTo();
    }
}
