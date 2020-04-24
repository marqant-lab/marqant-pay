<?php

namespace Marqant\MarqantPay\Contracts;

use Exception;

/**
 * Class PaymentMethodContract
 *
 * @package Marqant\MarqantPay\Contracts
 */
abstract class PaymentMethodContract
{
    /**
     * @var string The type of this payment.
     */
    protected string $type;

    /**
     * @var string The provider to use with this payment method.
     */
    protected string $provider;

    /**
     * @var string
     */
    protected string $provider_type;

    /**
     * @var array The details from the implemented payment method.
     */
    protected array $details;

    /**
     * @var string The class this payment method is supposed to represent.
     */
    protected string $provider_object;

    /**
     * @var object the actual object instance of the provider object.
     */
    public object $object;

    /**
     * PaymentMethodContract constructor.
     *
     * @param array $details
     */
    public function __construct(array $details)
    {
        $this->details = $details;
        $this->object = $this->createProviderObject($details);
    }

    /**
     * Create the provider object based on the provider_object attribute of the given payment method.
     *
     * @param array $details
     *
     * @return object
     */
    abstract protected function createProviderObject(array $details): object;

    /**
     * Get an array representation of this object.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'type'            => $this->type,
            'details'         => $this->details,
            'provider_object' => $this->provider_object,
            'object'          => $this->object,
        ];
    }

    /**
     * Getter for private and protected attributes of this object.
     *
     * @param $key
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function __get($key)
    {
        if (!$key || !isset($this->$key)) {
            $class = get_class($this);
            throw new Exception("No attribute {$key} available on {$class}.");
        }

        return $this->$key;
    }
}