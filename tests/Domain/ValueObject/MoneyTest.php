<?php

namespace Domain\ValueObject;

use InvalidArgumentException;
use Yashry\Domain\ValueObject\Currency;
use Yashry\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    private function createCurrency($code = 'USD', $symbol = '$', $usd_equivalent = 1){
        return new Currency($code, $symbol, $usd_equivalent);
    }
    /**
     * @test
     */
    public function moneyCantBeNegative()
    {
        $this->expectException(InvalidArgumentException::class);
        new Money($this->createCurrency(), -1);
    }

    /**
     * @test
     */
    public function differentCurrenciesCanBeAdded()
    {
        $a = new Money($this->createCurrency('EGP', 'LE', 0.05), 20);
        $b = new Money($this->createCurrency(), 20.0);
        $sum = $b->add($a);
        $this->assertSame(21.0, $sum->getValue());
    }

    /**
     * @test
     */
    public function moneyCanBeAdded()
    {
        $a = new Money($this->createCurrency(), 10);
        $b = new Money($this->createCurrency(), 20);
        $c = $a->add($b);
        $this->assertSame(30.0, $c->getValue());
    }

    /**
     * @test
     */
    public function moneyCanConverted()
    {
        $a = new Money($this->createCurrency(), 10);
        $egp = $this->createCurrency('EGP', 'LE', 0.05);
        $c = $a->convertTo($egp);
        $this->assertSame(200.0, $c->getValue());
    }


    /**
     * @test
     */
    public function moneyCanBeCreated()
    {
        $money = new Money($this->createCurrency(), 10);
        $this->assertInstanceOf(Money::class, $money);
    }


}
