<?php


namespace Yashry\Domain\Product\Service;


use Yashry\Domain\Product\Product;
use Yashry\Domain\ValueObject\Money;

/**
 * Implementation of a regular tax calculator
 * @package Yashry\Domain\Product\Service
 */
class RegularTaxCalculator implements ITaxCalculator
{
    const TAX_MULTIPLIER = 0.14;

    public function calculate(Product $for): Money
    {
        return new Money($for->getPrice()->getCurrency(), $for->getPrice()->getValue() * self::TAX_MULTIPLIER);
    }
}