<?php


namespace Yashry\Domain\Tax;


use Yashry\Domain\ValueObject\Money;

class RegularTaxCalculator implements ITaxCalculator
{

    public function calculate(Money $for): Money
    {
        return new Money($for->getCurrency(), $for->getValue() * 0.14);
    }
}