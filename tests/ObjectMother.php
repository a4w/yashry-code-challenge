<?php


use Yashry\Domain\ValueObject\Currency;
use Yashry\Domain\ValueObject\Money;

class ObjectMother
{
    public static function currency($code = 'USD', $symbol = '$', $usd_equivalent = 1){
        return new Currency($code, $symbol, $usd_equivalent);
    }

    public static function money(?Currency $currency, $value = 0){
        if($currency === null){
            $currency = self::currency();
        }
        return new Money($currency, $value);
    }
}