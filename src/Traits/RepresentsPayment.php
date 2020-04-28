<?php

namespace Marqant\MarqantPay\Traits;

use Rocky\Eloquent\HasDynamicRelation;
use Marqant\MarqantPay\Models\Relationships\BelongsToProviders;
use Marqant\MarqantPay\Models\Relationships\BelongsToManyBillables;

trait RepresentsPayment
{
    use BelongsToProviders;
    use HasDynamicRelation;
    use BelongsToManyBillables;
}