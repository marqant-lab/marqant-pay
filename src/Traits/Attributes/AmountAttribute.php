<?php

namespace Marqant\MarqantPay\Traits\Attributes;

use Money\Money;
use Money\Currency;
use Money\Currencies\ISOCurrencies;
use Money\Parser\DecimalMoneyParser;
use Money\Formatter\DecimalMoneyFormatter;
use Marqant\MarqantPay\Services\MarqantPay;

/**
 * Trait AmountAttribute
 *
 * @package Marqant\MarqantPay\Traits\Attributes
 */
trait AmountAttribute
{

    /**
     * Method to set the amount as integer value.
     *
     * @param string $amount
     *
     * @return void
     */
    public function setAmountAttribute(string $amount): void
    {
        $currency = strtoupper(MarqantPay::getCurrency()) ?? 'EUR';

        $parser = new DecimalMoneyParser(new ISOCurrencies());
        $money = $parser->parse($amount, new Currency($currency));

        // set value
        $this->attributes['amount'] = $money->getAmount();
    }

    /**
     * Method to get the amount as float value.
     *
     * @param null|int $amount
     *
     * @return string
     */
    public function getAmountAttribute(?int $amount): string
    {
        if (!$amount) {
            return 0;
        }

        $currency = strtoupper(MarqantPay::getCurrency()) ?? 'EUR';

        /**
         * @var Money $money
         */
        $money = Money::$currency($amount);
        $formatter = new DecimalMoneyFormatter(new ISOCurrencies());

        return $formatter->format($money);
    }

    /**
     * Method to set the amount as integer value.
     *
     * @param int $amount
     *
     * @return void
     */
    public function setAmountRawAttribute(int $amount): void
    {
        // set value
        $this->attributes['amount'] = $amount;
    }

    /**
     * Method to get the amount as integer value.
     *
     * @return int
     */
    public function getAmountRawAttribute(): int
    {
        return $this->attributes['amount'];
    }

    /**
     * Method to get the gross amount as float value.
     *
     * @return string
     */
    public function getAmountGrossAttribute(): string
    {
        $currency = strtoupper(MarqantPay::getCurrency()) ?? 'EUR';

        /**
         * @var Money $money
         */
        $money = Money::$currency($this->attributes['amount']);
        $amountGross = $money->add($money->multiply(config('marqant-pay.tax')));
        $formatter = new DecimalMoneyFormatter(new ISOCurrencies());

        return $formatter->format($amountGross);
    }

    /**
     * Method to get the raw gross amount as integer value.
     *
     * @return int
     */
    public function getAmountRawGrossAttribute(): int
    {
        $currency = strtoupper(MarqantPay::getCurrency()) ?? 'EUR';

        /**
         * @var Money $money
         */
        $money = Money::$currency($this->attributes['amount']);
        $amountGross = $money->add($money->multiply(config('marqant-pay.tax')));

        return $amountGross->getAmount();
    }

}
