<?php

namespace Marqant\MarqantPay\Traits;

use Rocky\Eloquent\HasDynamicRelation;
use Marqant\MarqantPay\Models\Relationships\BelongsToProviders;

trait RepresentsPayment
{
    use BelongsToProviders;
    use HasDynamicRelation;
}