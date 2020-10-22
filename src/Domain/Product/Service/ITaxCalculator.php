<?php


namespace Yashry\Domain\Product\Service;


use Yashry\Domain\Product\Product;
use Yashry\Domain\ValueObject\Money;

interface ITaxCalculator
{
    public function calculate(Product $for): Money;
}