<?php

namespace Yashry\Domain\ValueObject;

use InvalidArgumentException;

/**
 * Represents a currency where conversion is possible
 * @package Yashry\Domain\ValueObject
 */
class Currency
{
    private string $currency_code;
    private string $symbol;
    private float $usd_equivalent;
    private bool $is_amount_after_symbol;

    /**
     * Currency constructor.
     * @param String $currency_code
     * @param String $symbol
     * @param Float $usd_equivalent
     * @param bool $is_amount_after_symbol
     */
    public function __construct(string $currency_code = 'USD', string $symbol = '$', float $usd_equivalent = 1, bool $is_amount_after_symbol = true)
    {
        $this->setCurrencyCode($currency_code);
        $this->setSymbol($symbol);
        $this->setUsdEquivalent($usd_equivalent);
        $this->is_amount_after_symbol = $is_amount_after_symbol;
    }

    /**
     * @param String $currency_code
     * @throws InvalidArgumentException
     */
    private function setCurrencyCode(string $currency_code): void
    {
        if (!preg_match('/[A-Z]{3}/', $currency_code)) {
            throw new InvalidArgumentException('Currency code is must be three upper case characters');
        }
        $this->currency_code = $currency_code;
    }

    /**
     * @param String $symbol
     */
    private function setSymbol(string $symbol): void
    {
        if (strlen($symbol) === 0) {
            throw new InvalidArgumentException('Currency symbol cannot be empty');
        }
        $this->symbol = $symbol;
    }

    /**
     * @param float $usd_equivalent
     */
    private function setUsdEquivalent(float $usd_equivalent): void
    {
        if ($usd_equivalent <= 0) {
            throw new InvalidArgumentException('Currency usd equivalent must be positive');
        }
        $this->usd_equivalent = $usd_equivalent;
    }

    /**
     * @return String
     */
    public function getCurrencyCode(): string
    {
        return $this->currency_code;
    }

    /**
     * @return String
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @return float
     */
    public function getUsdEquivalent(): float
    {
        return $this->usd_equivalent;
    }

    /**
     * @return bool
     */
    public function isIsAmountAfterSymbol(): bool
    {
        return $this->is_amount_after_symbol;
    }

    public function equals(Currency $currency): Bool{
        return $this->getCurrencyCode() === $currency->getCurrencyCode();
    }

}
