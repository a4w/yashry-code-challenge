<?php


namespace Yashry\Domain\Tax;


use Yashry\Domain\ValueObject\Money;

interface ITaxCalculator
{
    public function calculate(Money $for): Money;
}