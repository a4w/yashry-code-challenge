<?php


namespace Yashry\Domain\ValueObject;


/**
 * Creates complex and basic money objects
 * @package Yashry\Domain\ValueObject
 */
class MoneyFactory
{
    /**
     * Creates the default amount of money equalling 0
     * @return Money
     */
    public static function zero()
    {
        return new Money(new Currency(), 0);
    }
}