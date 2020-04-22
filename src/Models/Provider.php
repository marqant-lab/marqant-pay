<?php

namespace Marqant\MarqantPay\Models;

use Illuminate\Database\Eloquent\Model;
use Marqant\MarqantPaySubscriptions\Models\Relationships\BelongsToManyPlans;

/**
 * Class Provider
 *
 * @mixin \Eloquent
 */
class Provider extends Model
{
    use BelongsToManyPlans;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
