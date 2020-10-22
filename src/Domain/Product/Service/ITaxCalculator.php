<?php


namespace Yashry\Domain\Product\Service;


use Yashry\Domain\ValueObject\Money;

interface ITaxCalculator
{
    public function calculate(Money $for): Money;
}