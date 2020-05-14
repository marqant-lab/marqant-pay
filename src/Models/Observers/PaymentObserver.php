<?php

namespace Marqant\MarqantPay\Models\Observers;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

class PaymentObserver
{
    /**
     * Hook on to the creating event from the eloquent model.
     *
     * @param \Illuminate\Database\Eloquent\Model $Payment
     *
     * @return void
     */
    public function creating(Model $Payment): void
    {
        /**
         * @var \Marqant\MarqantPay\Models\Payment $Payment
         */

        $this->addInvoiceNr($Payment);
    }

    /**
     * Hook on to the created event from the eloquent model.
     */
    public function created(Model $Payment): void
    {
        $Payment->createInvoice();
    }

    /**
     * Generate and fill the invoice nr on the given payment.
     *
     * @param \Illuminate\Database\Eloquent\Model $Payment
     *
     * @return void
     */
    private function addInvoiceNr(Model $Payment): void
    {
        $PaymentModel = app(config('marqant-pay.payment_model'));

        $year = Carbon::now()->year;
        $start = Carbon::now()
            ->firstOfYear();
        $end = Carbon::now()
            ->addYear()
            ->firstOfYear();

        $nr = 1 + $PaymentModel::where('created_at', '>=', $start)
                ->where('created_at', '<', $end)
                ->count();

        $Payment->invoice_nr = "{$nr}_{$year}";
    }
}