<?php


namespace Yashry\Domain\ValueObject;


class MoneyFactory
{
    public static function zero(){
        return new Money(new Currency(), 0);
    }
}