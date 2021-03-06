<?php


namespace Yashry\Domain\Offer\Service;


use Yashry\Domain\Cart\Cart;
use Yashry\Domain\Product\Product;
use Yashry\Domain\ValueObject\Money;
use Yashry\Domain\ValueObject\MoneyFactory;

/**
 * Represents an offer spec of the discount on a product given the purchase of another
 * @package Yashry\Domain\Offer\Service
 */
class BuyXProductGetYPercentOffProduct implements IOfferSpecification
{
    private Product $main;
    private Product $discounted;
    private int $test_quantity;
    private float $percent_off;
    private string $name;

    public function __construct(string $name, Product $main, int $test_quantity, Product $discounted, float $percent_off)
    {
        $this->name = $name;
        $this->main = $main;
        $this->discounted = $discounted;
        $this->test_quantity = $test_quantity;
        $this->percent_off = $percent_off;
    }

    public function isValidFor(Cart $cart): bool
    {
        $main_item = $cart->getCartItemForProduct($this->main);
        $discounted_item = $cart->getCartItemForProduct($this->discounted);
        if ($main_item === null || $discounted_item === null) {
            return false;
        }
        if ($main_item->getQuantity() < $this->test_quantity || $discounted_item->getQuantity() < 1) {
            return false;
        }
        return true;
    }

    public function calculateOfferValue(Cart $cart): Money
    {
        if (!self::isValidFor($cart)) {
            return MoneyFactory::zero();
        }
        $main_item = $cart->getCartItemForProduct($this->main);
        $discounted_item = $cart->getCartItemForProduct($this->discounted);
        $main_item_quantity = $main_item->getQuantity();
        $discounted_item_quantity = $discounted_item->getQuantity();
        $available_offer_times = intval($main_item_quantity / $this->test_quantity);
        $applied_offer_times = min($available_offer_times, $discounted_item_quantity);
        $total_discounted_value = $applied_offer_times * (($this->percent_off / 100) * $this->discounted->getPrice()->getValue());
        return new Money($this->discounted->getPrice()->getCurrency(), $total_discounted_value);
    }

    public function getOfferName(): string
    {
        return $this->name;
    }
}