<?php

namespace Yashry;

use Yashry\Domain\Product\Product;
use Yashry\Domain\Product\Service\ITaxCalculator;
use Yashry\Domain\ValueObject\Currency;
use Yashry\Domain\ValueObject\Money;

class ObjectMother
{
    public static function currency($code = 'USD', $symbol = '$', $usd_equivalent = 1){
        return new Currency($code, $symbol, $usd_equivalent);
    }

    public static function money(?Currency $currency = null, $value = 0){
        if($currency === null){
            $currency = self::currency();
        }
        return new Money($currency, $value);
    }

    public static function product($title = 'T-shirt', ?Money $price = null){
        if($price === null){
            $price = self::money();
        }
        return new Product($title, $price);
    }

    public static function constantTaxCalculator($value = 1): ITaxCalculator{
        return new ConstantTaxCalculator($value);
    }

}

class ConstantTaxCalculator implements ITaxCalculator{
    private Float $tax;
    public function __construct(Float $value){
        $this->tax = $value;
    }
    public function calculate(Product $for): Money
    {
        return new Money($for->getPrice()->getCurrency(), $this->tax);
    }
}
