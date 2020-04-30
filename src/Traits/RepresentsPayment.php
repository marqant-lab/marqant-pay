<?php

namespace Marqant\MarqantPay\Traits;

use Rocky\Eloquent\HasDynamicRelation;
use Marqant\MarqantPay\Models\Relationships\BelongsToProvider;
use Marqant\MarqantPay\Models\Relationships\BelongsToManyBillables;

trait RepresentsPayment
{
    use BelongsToProvider;
    use HasDynamicRelation;
    use BelongsToManyBillables;
}