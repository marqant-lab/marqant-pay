<?php

namespace Marqant\MarqantPay\Services;

use Marqant\MarqantPay\Models\Payment;
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
     * Resolve the payment provider of a billable model.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     *
     * @return \Marqant\MarqantPay\Contracts\PaymentGatewayContract
     * @throws \Exception
     */
    public static function resolveProviderGateway(Model $Billable): PaymentGatewayContract
    {
        self::validateProvider($Billable->marqant_pay_provider);

        $gateway = config('marqant-pay.gateways.' . $Billable->marqant_pay_provider, null);

        return app($gateway);
    }

    /**
     * Method to create a customer on the provider end.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     * @param string                              $provider
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public static function createCustomer(Model $Billable, string $provider)
    {
        self::validateProvider($provider);

        $Billable->marqant_pay_provider = $provider;

        $ProviderGateway = self::resolveProviderGateway($Billable);

        return $ProviderGateway->createCustomer($Billable);
    }

    /**
     * Charge the billable through the payment provider associated with the user.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     * @param int                                 $amount
     *
     * @return \Marqant\MarqantPay\Models\Payment
     * @throws \Exception
     */
    public static function charge(Model $Billable, int $amount): Payment
    {
        $ProviderGateway = self::resolveProviderGateway($Billable);

        $Payment = $ProviderGateway->charge($Billable, $amount);

        return $Payment;
    }

    /**
     * @param string $provider
     *
     * @return void
     *
     * @throws \Exception
     */
    private static function validateProvider(string $provider): void
    {
        $providers = config('marqant-pay.gateways.' . $provider, null);

        if (!$provider) {
            throw new \Exception('Provider not available.');
        }
    }
}