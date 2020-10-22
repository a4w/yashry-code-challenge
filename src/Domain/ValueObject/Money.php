<?php


namespace Yashry\Domain\ValueObject;



use InvalidArgumentException;

class Money
{
    private Currency $currency;
    private Float $value;

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
        if($value < 0){
            throw new InvalidArgumentException('Money cannot be negative');
        }
        $this->value = $value;
    }

    public function add(Money $money){
        if($this->currency != $money->currency){
            throw new InvalidArgumentException('Cannot add money with different currencies');
        }
        return new self($this->currency, $money->getValue() + $this->getValue());
    }

}