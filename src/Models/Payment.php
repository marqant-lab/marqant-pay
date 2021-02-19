<?php

namespace Marqant\MarqantPay\Models;

use Illuminate\Database\Eloquent\Model;
use Marqant\MarqantPay\Traits\RepresentsInvoice;
use Marqant\MarqantPay\Traits\RepresentsPayment;

/**
 * Class Payment
 *
 * @mixin \Eloquent
 */
class Payment extends Model
{
    const STATUS_FAILED = 'failed';

    use RepresentsPayment;
    use RepresentsInvoice;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get the raw amount from the database as integer.
     *
     * @return int
     */
    public function getAmountRawAttribute(): int
    {
        return $this->attributes['amount'];
    }
}
