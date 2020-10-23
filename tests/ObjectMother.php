<?php

namespace Yashry;

use Yashry\Domain\Cart\Cart;
use Yashry\Domain\Offer\Service\IOfferSpecification;
use Yashry\Domain\Product\Product;
use Yashry\Domain\Product\Service\ITaxCalculator;
use Yashry\Domain\ValueObject\Currency;
use Yashry\Domain\ValueObject\Money;

class ObjectMother
{
    public static function currency($code = 'USD', $symbol = '$', $usd_equivalent = 1, $is_amount_after_symbol = true)
    {
        return new Currency($code, $symbol, $usd_equivalent, $is_amount_after_symbol);
    }

    public static function money(?Currency $currency = null, $value = 0)
    {
        if ($currency === null) {
            $currency = self::currency();
        }
        return new Money($currency, $value);
    }

    public static function product($title = 'T-shirt', ?Money $price = null)
    {
        if ($price === null) {
            $price = self::money();
        }
        return new Product($title, $price);
    }

    public static function constantTaxCalculator($value = 1): ITaxCalculator
    {
        return new ConstantTaxCalculator($value);
    }

    public static function constantOffer(Money $amount = null, string $name = "Constant offer"): IOfferSpecification
    {
        if ($amount === null) {
            $amount = self::money();
        }
        return new ConstantOffer($amount, $name);
    }

}

class ConstantTaxCalculator implements ITaxCalculator
{
    private float $tax;

    public function __construct(float $value)
    {
        $this->tax = $value;
    }

    public function calculate(Product $for): Money
    {
        return new Money($for->getPrice()->getCurrency(), $this->tax);
    }
}

class ConstantOffer implements IOfferSpecification
{

    private Money $value;
    private string $name;

    public function __construct(Money $value, string $name = "Constant offer")
    {
        $this->value = $value;
        $this->name = $name;
    }

    public function isValidFor(Cart $cart): bool
    {
        return true;
    }

    public function calculateOfferValue(Cart $cart): Money
    {
        return $this->value;
    }

    public function getOfferName(): string
    {
        return $this->name;
    }
}
