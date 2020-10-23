<?php


namespace Yashry\Domain\ValueObject;


use InvalidArgumentException;

/**
 * Represents a non-negative amount of money along with it's currency
 * @package Yashry\Domain\ValueObject
 */
class Money
{
    private Currency $currency;
    private float $value;

    /**
     * Money constructor.
     * @param Currency $currency
     * @param Float $value
     */
    public function __construct(Currency $currency, float $value)
    {
        $this->setCurrency($currency);
        $this->setValue($value);
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @param Currency $currency
     */
    private function setCurrency(Currency $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param float $value
     */
    private function setValue(float $value): void
    {
        if ($value < 0) {
            throw new InvalidArgumentException('Money cannot be negative');
        }
        $this->value = $value;
    }

    public function add(Money $money)
    {
        $converted = $money->convertTo($this->getCurrency());
        return new self($this->currency, $converted->getValue() + $this->getValue());
    }

    public function subtract(Money $money)
    {
        $converted = $money->convertTo($this->getCurrency());
        return new self($this->currency, abs($converted->getValue() - $this->getValue()));
    }

    public function convertTo(Currency $currency)
    {
        $value = $this->getValue() * $this->getCurrency()->getUsdEquivalent();
        $new_value = $value / $currency->getUsdEquivalent();
        return new self($currency, $new_value);
    }

    public function equals(Money $money): bool
    {
        return $this->getValue() === $money->getValue() && $this->getCurrency()->equals($money->getCurrency());
    }

    public function __toString()
    {
        $str = (string)$this->getValue();
        if ($this->getCurrency()->isIsAmountAfterSymbol()) {
            $str = $this->getCurrency()->getSymbol() . $str;
        } else {
            $str .= $this->getCurrency()->getSymbol();
        }
        return $str;
    }

}