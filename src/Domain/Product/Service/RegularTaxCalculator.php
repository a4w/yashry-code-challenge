<?php


namespace Yashry\Domain\Product\Service;


use Yashry\Domain\Product\Product;
use Yashry\Domain\ValueObject\Money;

class RegularTaxCalculator implements ITaxCalculator
{

    public function calculate(Product $for): Money
    {
        return new Money($for->getPrice()->getCurrency(), $for->getPrice()->getValue() * 0.14);
    }
}