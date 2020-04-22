<?php

namespace Marqant\MarqantPay\Models;

use Illuminate\Database\Eloquent\Model;
use Marqant\MarqantPay\Traits\RepresentsProvider;

/**
 * Class Provider
 *
 * @mixin \Eloquent
 */
class Provider extends Model
{
    use RepresentsProvider;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
