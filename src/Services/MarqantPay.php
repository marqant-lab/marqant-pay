<?php

namespace Marqant\MarqantPay\Services;

use Exception;
use ReflectionClass;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\Macroable;
use Marqant\MarqantPay\Contracts\PaymentMethodContract;
use Marqant\MarqantPay\Contracts\PaymentGatewayContract;

/**
 * Class MarqantPay
 *
 * @package Marqant\MarqantPay\Services
 *
 * @mixin \Marqant\MarqantPaySubscriptions\Mixins\MarqantPaySubscriptionsMixin
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
    public static function resolveProviderGateway(Model $Billable,
                                                  ?PaymentMethodContract $PaymentMethod = null): PaymentGatewayContract
    {
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
    public static function createCustomer(Model &$Billable, string $provider): Model
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
     * @param string                                                   $description
     * @param null|\Marqant\MarqantPay\Contracts\PaymentMethodContract $PaymentMethod
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Exception
     */
    public static function charge(Model $Billable, int $amount, string $description,
                                  ?PaymentMethodContract $PaymentMethod = null): Model
    {
        $ProviderGateway = self::resolveProviderGateway($Billable, $PaymentMethod);

        return $ProviderGateway->charge($Billable, $amount, $description, $PaymentMethod);
    }

    /**
     * Update Payment status through received payment provider
     *
     * @param \Illuminate\Database\Eloquent\Model $Payment
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws Exception
     */
    public static function updatePaymentStatus(Model $Payment): Model
    {
        $ProviderGateway = self::resolveProviderGatewayFromString($Payment->provider);

        return $ProviderGateway->updatePaymentStatus($Payment);
    }

    /**
     * Create Payment using provider data
     *
     * @param string $provider
     * @param string $payment_id
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Exception
     */
    public static function createPaymentByProviderPaymentID(string $provider, string $payment_id): Model
    {
        $ProviderGateway = self::resolveProviderGatewayFromString($provider);

        return $ProviderGateway->createPaymentByProviderPaymentID($payment_id);
    }

    /**
     * Get or create (if not exists) Payment using provider data
     *
     * @param string $provider
     * @param array  $invoice_data
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Exception
     */
    public static function getPaymentByInvoice(string $provider, array $invoice_data): Model
    {
        $ProviderGateway = self::resolveProviderGatewayFromString($provider);

        return $ProviderGateway->getPaymentByInvoice($invoice_data);
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
        if (!$Billable->marqant_pay_provider) {
            return false;
        }

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
     * @param \Illuminate\Database\Eloquent\Model                 $Billable
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
     * Create invoices for a given payment.
     *
     * @param \Illuminate\Database\Eloquent\Model $Payment
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function createInvoice(Model $Payment): Model
    {
        /**
         * @var \Marqant\MarqantPay\Services\ProviderInvoice $InvoiceService
         */
        $InvoiceService = app(config('marqant-pay.invoice_service'));

        return $InvoiceService->createInvoice($Payment);
    }

    /**
     * Get model instances from config.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getBillables(): Collection
    {
        // get billable classes
        // - get array from config
        // - map through the array/collection and get instances of the models
        return collect(config('marqant-pay.billables'))->map(function ($billable) {
            return app($billable);
        });
    }

}
