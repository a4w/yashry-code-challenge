<?php


namespace Yashry\Domain\Cart;


use InvalidArgumentException;
use Yashry\Domain\Product\Product;

/**
 * Represents a product inside a cart, along with it's quantity
 * @package Yashry\Domain\Cart
 */
class CartItem
{
    private Product $product;
    private int $quantity;

    /**
     * CartItem constructor.
     * @param Product $product
     * @param Int $quantity
     */
    public function __construct(Product $product, int $quantity)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        if ($quantity < 0) {
            throw new InvalidArgumentException('Quantity cannot be negative');
        }
        $this->quantity = $quantity;
    }

    public function increaseQuantity()
    {
        $this->setQuantity($this->quantity + 1);
    }

    public function decreaseQuantity()
    {
        $this->setQuantity($this->quantity - 1);
    }


}