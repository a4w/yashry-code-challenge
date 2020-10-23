<?php


namespace Yashry\Domain\Offer\Service;


use Yashry\Domain\Cart\Cart;
use Yashry\Domain\Product\Product;
use Yashry\Domain\ValueObject\Money;
use Yashry\Domain\ValueObject\MoneyFactory;

/**
 * Represents an offer spec for a fixed discount on a specific product
 * @package Yashry\Domain\Offer\Service
 */
class ProductDiscountedPercent implements IOfferSpecification
{
    private Product $target;
    private float $discount_percent;
    private string $name;

    /**
     * ProductDiscountedPercent constructor.
     * @param Product $target
     * @param Float $discount_percent
     */
    public function __construct(string $name, Product $target, float $discount_percent)
    {
        $this->target = $target;
        $this->discount_percent = $discount_percent;
        $this->name = $name;
    }

    public function isValidFor(Cart $cart): bool
    {
        return $cart->getCartItemForProduct($this->target) !== null;
    }

    public function calculateOfferValue(Cart $cart): Money
    {
        if (!self::isValidFor($cart)) {
            return MoneyFactory::zero();
        }
        $cart_item = $cart->getCartItemForProduct($this->target);
        $quantity = $cart_item->getQuantity();
        $discount_per_item = $this->target->getPrice()->getValue() * ($this->discount_percent / 100);
        return new Money($this->target->getPrice()->getCurrency(), $quantity * $discount_per_item);
    }

    public function getOfferName(): string
    {
        return $this->name;
    }
}