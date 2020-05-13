<?php

namespace Marqant\MarqantPay\Traits;

use Rocky\Eloquent\HasDynamicRelation;
use Marqant\MarqantPay\Models\Relationships\BelongsToProvider;
use Marqant\MarqantPay\Models\Relationships\BelongsToBillable;

trait RepresentsPayment
{
    use Invoiceable;
    use BelongsToProvider;
    use HasDynamicRelation;
    use BelongsToBillable;
}