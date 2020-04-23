<?php

namespace Marqant\MarqantPay\Models;

use Illuminate\Database\Eloquent\Model;
use Marqant\MarqantPay\Traits\RepresentsPayment;

/**
 * Class Payment
 *
 * @mixin \Eloquent
 */
class Payment extends Model
{
    use RepresentsPayment;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
