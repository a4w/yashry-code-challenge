<?php


namespace Yashry\Domain\Offer\Service;


use Yashry\Domain\Cart\Cart;
use Yashry\Domain\Product\Product;
use Yashry\Domain\ValueObject\Money;

class BuyXProductGetYPercentOffProduct implements IOfferSpecification
{
    private Product $main;
    private Product $discounted;
    private Int $test_quantity;
    private Float $percent_off;

    public function __construct(Product $main, Int $test_quantity, Product $discounted, Float $percent_off){
        $this->main = $main;
        $this->discounted = $discounted;
        $this->test_quantity = $test_quantity;
        $this->percent_off = $percent_off;
    }

    public function isValidFor(Cart $cart): Bool
    {
        $discounted = self::calculateOfferValue($cart);
        return $discounted > 0;
    }

    public function calculateOfferValue(Cart $cart): Money
    {
        $main_item = $cart->getCartItemForProduct($this->main);
        $main_item_quantity = $main_item->getQuantity();
        $available_offer_times = intval($main_item_quantity / $this->test_quantity);
        $discounted_item_quantity = $cart->getCartItemForProduct($this->discounted)->getQuantity();
        $applied_offer_times = min($available_offer_times, $discounted_item_quantity);
        $total_discounted_value = $applied_offer_times * (($this->percent_off/100) * $this->discounted->getPrice()->getValue());
        return new Money($this->discounted->getPrice()->getCurrency(), $total_discounted_value);
    }
}