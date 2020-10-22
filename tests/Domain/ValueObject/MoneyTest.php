<?php

namespace Domain\ValueObject;

use InvalidArgumentException;
use Yashry\Domain\ValueObject\Currency;
use Yashry\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    private function createCurrency(){
        return new Currency('USD', '$', 1);
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
    public function differentCurrenciesCantBeAdded()
    {
        $this->expectException(InvalidArgumentException::class);
        $a = new Money(new Currency('EUR', 'E', 1.3), 10);
        $b = new Money($this->createCurrency(), 20.0);
        $a->add($b);
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
    public function moneyCanBeCreated()
    {
        $money = new Money($this->createCurrency(), 10);
        $this->assertInstanceOf(Money::class, $money);
    }


}
