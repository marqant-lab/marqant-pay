<?php

namespace Marqant\MarqantPay\Traits;

use Illuminate\Support\Carbon;
use Rocky\Eloquent\HasDynamicRelation;
use Illuminate\Database\Eloquent\Model;
use Marqant\MarqantPay\Models\Relationships\BelongsToProvider;
use Marqant\MarqantPay\Models\Relationships\BelongsToManyBillables;

trait RepresentsPayment
{
    use Invoiceable;
    use BelongsToProvider;
    use HasDynamicRelation;
    use BelongsToManyBillables;

    protected static function booted()
    {
        // add invoice_nr to payment on save
        static::creating(function (Model $Payment) {
            /**
             * @var \Marqant\MarqantPay\Models\Payment $Payment
             */

            $year = Carbon::now()->year;

            $start = Carbon::now()
                ->firstOfYear();
            $end = Carbon::now()
                ->addYear()
                ->firstOfYear();

            $nr = 1 + self::where('created_at', '=>', $start)
                    ->where('created_at', '<', $end)
                    ->count();

            $Payment->invoice_nr = "{$nr}_{$year}";
        });

        parent::booted();
    }
}