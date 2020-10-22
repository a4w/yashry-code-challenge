<?php


namespace Yashry\Domain\Cart;


use Yashry\Domain\Product\Product;

class CartFactory
{
    /**
     * @param Product[] $products
     * @return Cart
     */
    public static function createFromProducts(array $products): Cart
    {
        $cart = new Cart();
        foreach ($products as $product) {
            $cart->addProduct($product);
        }
        return $cart;
    }
}