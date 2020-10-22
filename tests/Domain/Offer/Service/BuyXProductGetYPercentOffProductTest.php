<?php

namespace Domain\Offer\Service;

use Yashry\Domain\Cart\Cart;
use Yashry\Domain\Offer\Service\BuyXProductGetYPercentOffProduct;
use PHPUnit\Framework\TestCase;
use Yashry\ObjectMother;

class BuyXProductGetYPercentOffProductTest extends TestCase
{
    /**
     * @test
     */
    public function calculateOfferPresentCorrectly()
    {
        $spec = new BuyXProductGetYPercentOffProduct(ObjectMother::product(), 2, ObjectMother::product('Shorts', ObjectMother::money(null, 10)), 50);
        $cart = new Cart();
        // Add two of test products
        $cart->addProduct(ObjectMother::product());
        $cart->addProduct(ObjectMother::product());
        // Add one of discounted products
        $cart->addProduct(ObjectMother::product('Shorts'));
        $offer_value = $spec->calculateOfferValue($cart);
        $this->assertTrue($offer_value->equals(ObjectMother::money(null, 5)));
    }

    /**
     * @test
     */
    public function calculateOfferNotPresentCorrectly()
    {
        $spec = new BuyXProductGetYPercentOffProduct(ObjectMother::product(), 5, ObjectMother::product('Shorts', ObjectMother::money(null, 10)), 50);
        $cart = new Cart();
        // Add two of test products
        $cart->addProduct(ObjectMother::product());
        $cart->addProduct(ObjectMother::product());
        // Add one of discounted products
        $cart->addProduct(ObjectMother::product('Shorts'));
        $offer_value = $spec->calculateOfferValue($cart);
        $this->assertTrue($offer_value->equals(ObjectMother::money(null, 0)));
    }

}
