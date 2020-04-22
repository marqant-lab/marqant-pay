<?php

namespace Marqant\MarqantPay\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\Macroable;
use Marqant\MarqantPay\Contracts\PaymentGatewayContract;

class MarqantPayService
{
    /**
     * We make use of the Macroable trait to make this service extendable. That way, other packages can bring in
     * functionality, without requiring much boilerplate.
     */
    use Macroable;

    /**
     * Charge the billable through the payment provider associated with the user.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     * @param int                                 $amount
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function charge(Model $Billable, int $amount): Model
    {
        $ProviderGateway = self::resolveProviderGateway($Billable);

        // $ProviderGateway->charge($Billable, $amount);

        return $Billable;
    }

    /**
     * Resolve the payment provider of a billable model.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     *
     * @return \Marqant\MarqantPay\Contracts\PaymentGatewayContract
     */
    public static function resolveProviderGateway(Model $Billable): PaymentGatewayContract
    {
        $gateway = config('marqant-pay.gateways.' . $Billable->marqant_pay_provider, null);

        return app($gateway);
    }
}