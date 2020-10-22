<?php

namespace Domain\Offer\Service;

use Yashry\Domain\Cart\Cart;
use Yashry\Domain\Offer\Service\ProductDiscountedPercent;
use PHPUnit\Framework\TestCase;
use Yashry\ObjectMother;

class ProductDiscountedPercentTest extends TestCase
{
    /**
     * @test
     */
    public function calculateOfferPresentCorrectly()
    {
        $spec = new ProductDiscountedPercent("", ObjectMother::product('T-shirt', ObjectMother::money(null, 10)), 50);
        $cart = new Cart();
        $cart->addProduct(ObjectMother::product('T-shirt'));
        $cart->addProduct(ObjectMother::product('T-shirt'));
        $offer_value = $spec->calculateOfferValue($cart);
        $this->assertTrue($offer_value->equals(ObjectMother::money(null, 10)));
    }

    /**
     * @test
     */
    public function calculateOfferNotFulfilledCorrectly()
    {
        $spec = new ProductDiscountedPercent("", ObjectMother::product('T-shirt', ObjectMother::money(null, 10)), 50);
        $cart = new Cart();
        $cart->addProduct(ObjectMother::product('Shorts'));
        $offer_value = $spec->calculateOfferValue($cart);
        $this->assertTrue($offer_value->equals(ObjectMother::money(null, 0)));
    }

}
