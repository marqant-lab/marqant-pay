<?php

namespace Marqant\MarqantPay\Services;

use Exception;
use ReflectionClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\Macroable;
use Marqant\MarqantPay\Contracts\PaymentMethodContract;
use Marqant\MarqantPay\Contracts\PaymentGatewayContract;

/**
 * Class MarqantPay
 *
 * @package Marqant\MarqantPay\Services
 *
 * @mixin \Marqant\MarqantPaySubscriptions\Mixins\MarqantPayMixin
 */
class MarqantPay
{
    /**
     * We make use of the Macroable trait to make this service extendable. That way, other packages can bring in
     * functionality, without requiring much boilerplate.
     */
    use Macroable;

    /**
     * Resolve the payment provider of a billable model.
     *
     * @param \Illuminate\Database\Eloquent\Model                      $Billable
     * @param null|\Marqant\MarqantPay\Contracts\PaymentMethodContract $PaymentMethod
     *
     * @return \Marqant\MarqantPay\Contracts\PaymentGatewayContract
     * @throws \Exception
     */
    public static function resolveProviderGateway(
        Model $Billable,
        ?PaymentMethodContract $PaymentMethod = null
    ): PaymentGatewayContract {
        $provider = $Billable->marqant_pay_provider;

        if ($PaymentMethod) {
            $provider = $PaymentMethod->provider;
        }

        return self::resolveProviderGatewayFromString($provider);
    }

    /**
     * Resolve the provider by a given string.
     *
     * @param string $provider
     *
     * @return \Illuminate\Contracts\Foundation\Application|mixed
     * @throws \Exception
     */
    public static function resolveProviderGatewayFromString(string $provider)
    {
        self::validateProvider($provider);

        $gateway = config("marqant-pay.gateways.{$provider}", null);

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
    public static function createCustomer(Model $Billable, string $provider): Model
    {
        self::validateProvider($provider);

        $Billable->marqant_pay_provider = $provider;

        $ProviderGateway = self::resolveProviderGateway($Billable);

        return $ProviderGateway->createCustomer($Billable);
    }

    /**
     * Charge the billable through the payment provider associated with the user.
     *
     * @param \Illuminate\Database\Eloquent\Model                      $Billable
     * @param int                                                      $amount
     * @param null|\Marqant\MarqantPay\Contracts\PaymentMethodContract $PaymentMethod
     * @param null|string                                              $description
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Exception
     */
    public static function charge(Model $Billable, float $amount, ?PaymentMethodContract $PaymentMethod = null, ?string $description = null): Model
    {
        $ProviderGateway = self::resolveProviderGateway($Billable, $PaymentMethod);

        return $ProviderGateway->charge($Billable, $amount, $PaymentMethod, $description);
    }

    /**
     * Validate a provider string against the configuration.
     *
     * @param null|string $provider
     *
     * @return void
     *
     * @throws \Exception
     */
    private static function validateProvider(?string $provider): void
    {
        if (!$provider) {
            throw new Exception("No provider ... 'provided' 😉");
        }

        $found_provider = config('marqant-pay.gateways.' . $provider, false);

        if (!$found_provider) {
            throw new Exception('Provider not available.');
        }
    }

    /**
     * Resolve a incoming payment method data to object.
     *
     * @param string $type
     * @param array  $details
     *
     * @return \Marqant\MarqantPay\Contracts\PaymentMethodContract
     *
     * @throws \Exception
     */
    public static function resolvePaymentMethod(string $type, array $details = []): PaymentMethodContract
    {
        $resolver = config('marqant-pay.payment_methods.' . $type, null);

        if (!$resolver) {
            throw new Exception("Could not resolve given payment method: '{$type}'");
        }

        self::checkImplementationOfPaymentMethod($type, $resolver);

        return new $resolver($details);
    }

    /**
     * Check that the payment method provided in the configuration implements the PaymentMethodContract class.
     *
     * @param string      $type
     * @param string|null $resolver
     *
     * @throws \ReflectionException
     *
     * @throws \Exception
     */
    private static function checkImplementationOfPaymentMethod(string $type, ?string $resolver): void
    {
        $contract = PaymentMethodContract::class;

        if (!(new ReflectionClass($resolver))->isSubclassOf($contract)) {
            $message = "Resolver for payment method {$type} does not implement {$contract}";
            throw new Exception($message);
        }
    }

    /**
     * Saves a payment method on the billable.
     *
     * @param \Illuminate\Database\Eloquent\Model                 $Billable
     * @param \Marqant\MarqantPay\Contracts\PaymentMethodContract $PaymentMethod
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public static function savePaymentMethod(Model &$Billable, PaymentMethodContract $PaymentMethod)
    {
        $ProviderGateway = self::resolveProviderGateway($Billable);

        $ProviderGateway->savePaymentMethod($Billable, $PaymentMethod);

        return $Billable;
    }

    /**
     * Check if billable has a payment method attached.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     *
     * @return bool
     *
     * @throws \Exception
     */
    public static function hasPaymentMethod(Model $Billable): bool
    {
        $ProviderGateway = self::resolveProviderGateway($Billable);

        return $ProviderGateway->hasPaymentMethod($Billable);
    }

    /**
     * Check if billable has a payment method attached.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     *
     * @return \Marqant\MarqantPay\Contracts\PaymentMethodContract
     *
     * @throws \Exception
     */
    public static function getPaymentMethodOfBillable(Model $Billable): PaymentMethodContract
    {
        $ProviderGateway = self::resolveProviderGateway($Billable);

        return $ProviderGateway->getPaymentMethodOfBillable($Billable);
    }

    /**
     * Remove payment method from billable.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     *
     * @param \Marqant\MarqantPay\Contracts\PaymentMethodContract $PaymentMethod
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Exception
     */
    public static function removePaymentMethod(Model &$Billable, PaymentMethodContract $PaymentMethod): Model
    {
        $ProviderGateway = self::resolveProviderGateway($Billable);

        return $ProviderGateway->removePaymentMethod($Billable, $PaymentMethod);
    }

    /**
     * Get the current currency.
     *
     * @return string
     */
    public static function getCurrency(): string
    {
        return config('marqant-pay.default_currency');
    }

    /**
     * Create the plan on the provider end.
     *
     * @param \Illuminate\Database\Eloquent\Model $Plan
     * @param string                              $provider
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public static function createPlan(Model $Plan, string $provider): Model
    {
        $ProviderGateway = self::resolveProviderGatewayFromString($provider);

        return $ProviderGateway->createPlan($Plan);
    }

    /**
     * Update Payment status through received payment provider
     *
     * @param \Illuminate\Database\Eloquent\Model $Payment
     * @param string|null                         $status
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws Exception
     */
    public static function updatePaymentStatus(Model $Payment, $status = null): Model
    {
        $ProviderGateway = self::resolveProviderGatewayFromString($Payment->provider);

        return $ProviderGateway->updatePaymentStatus($Payment, $status);
    }

    /**
     * Send email if Payment failed through received payment provider
     *
     * @param \Illuminate\Database\Eloquent\Model $Payment
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Exception
     */
    public static function sendEmailFailedPayment(Model $Payment)
    {
        $ProviderGateway = self::resolveProviderGatewayFromString($Payment->provider);

        return $ProviderGateway->sendEmailFailedPayment($Payment);
    }

    /**
     * Send email if Payment failed to support emails
     *
     * @param \Illuminate\Database\Eloquent\Model $Payment
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Exception
     */
    public static function sendSupportEmailFailedPayment(Model $Payment)
    {
        $ProviderGateway = self::resolveProviderGatewayFromString($Payment->provider);

        return $ProviderGateway->sendSupportEmailFailedPayment($Payment);
    }
}
