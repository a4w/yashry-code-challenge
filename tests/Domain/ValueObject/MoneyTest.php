<?php

namespace Domain\ValueObject;

use InvalidArgumentException;
use ObjectMother;
use Yashry\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    /**
     * @test
     */
    public function moneyCantBeNegative()
    {
        $this->expectException(InvalidArgumentException::class);
        new Money(ObjectMother::currency(), -1);
    }

    /**
     * @test
     */
    public function differentCurrenciesCanBeAdded()
    {
        $a = new Money(ObjectMother::currency('EGP', 'LE', 0.05), 20);
        $b = new Money(ObjectMother::currency(), 20.0);
        $sum = $b->add($a);
        $this->assertSame(21.0, $sum->getValue());
    }

    /**
     * @test
     */
    public function moneyCanBeAdded()
    {
        $a = new Money(ObjectMother::currency(), 10);
        $b = new Money(ObjectMother::currency(), 20);
        $c = $a->add($b);
        $this->assertSame(30.0, $c->getValue());
    }

    /**
     * @test
     */
    public function moneyCanConverted()
    {
        $a = new Money(ObjectMother::currency(), 10);
        $egp = ObjectMother::currency('EGP', 'LE', 0.05);
        $c = $a->convertTo($egp);
        $this->assertSame(200.0, $c->getValue());
    }


    /**
     * @test
     */
    public function moneyCanBeCreated()
    {
        $money = new Money(ObjectMother::currency(), 10);
        $this->assertInstanceOf(Money::class, $money);
    }


}
