<?php


namespace Yashry\Domain\Product\Service;


use Yashry\Domain\Product\Product;
use Yashry\Domain\ValueObject\Money;

/**
 * Represents a tax calculation algorithm
 * @package Yashry\Domain\Product\Service
 */
interface ITaxCalculator
{
    public function calculate(Product $for): Money;
}