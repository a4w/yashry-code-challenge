<?php

namespace Yashry\Domain\Cart;

use PHPUnit\Framework\TestCase;
use Yashry\Domain\Offer\Service\IOfferSpecification;
use Yashry\Domain\ValueObject\Money;
use Yashry\ObjectMother;

class CartTest extends TestCase
{
    /**
     * @test
     */
    public function canAddNewProductToCart()
    {
        $cart = new Cart();
        $cart->addProduct(ObjectMother::product());
        $this->assertCount(1, $cart->getItems());
    }

    /**
     * @test
     */
    public function canAddExistingProductToCart()
    {
        $cart = new Cart();
        $cart->addProduct(ObjectMother::product());
        $cart->addProduct(ObjectMother::product());
        $this->assertCount(1, $cart->getItems());
        $this->assertSame(2, $cart->getItems()[0]->getQuantity());
    }

    /**
     * @test
     */
    public function canCalculateSubtotal()
    {
        $cart = new Cart();
        $cart->addProduct(ObjectMother::product('T-shirt', ObjectMother::money(null, 10)));
        $cart->addProduct(ObjectMother::product('Shorts', ObjectMother::money(null, 30)));
        $subtotal = $cart->getSubtotal();
        $this->assertSame(40.0, $subtotal->getValue());
    }

    /**
     * @test
     */
    public function canCalculateTax()
    {
        $cart = new Cart();
        $cart->addProduct(ObjectMother::product('T-shirt', ObjectMother::money(null, 10)));
        $cart->addProduct(ObjectMother::product('T-shirt', ObjectMother::money(null, 10)));
        $cart->addProduct(ObjectMother::product('Shorts', ObjectMother::money(null, 30)));
        $taxes = $cart->getTaxesTotal(ObjectMother::constantTaxCalculator(10));
        $this->assertSame(30.0, $taxes->getValue());
    }

    /**
     * @test
     */
    public function canCalculateOffersWhenPresent()
    {
        $cart = new Cart();
        $cart->addProduct(ObjectMother::product('T-shirt', ObjectMother::money(null, 10)));
        $cart->addProduct(ObjectMother::product('T-shirt', ObjectMother::money(null, 10)));
        $cart->addProduct(ObjectMother::product('Shorts', ObjectMother::money(null, 30)));
        $offers_specs = [ObjectMother::constantOffer(ObjectMother::money(null, 5)), ObjectMother::constantOffer(ObjectMother::money(null, 10))];
        $offers = $cart->getAvailableOffers($offers_specs);
        $this->assertCount(2, $offers);
        $this->assertTrue(ObjectMother::money(null,5)->equals($offers[0]->getDiscountValue()));
        $this->assertTrue(ObjectMother::money(null,10)->equals($offers[1]->getDiscountValue()));
    }

    /**
     * @test
     */
    public function canCalculateOffersWhenNotPresent()
    {
        $cart = new Cart();
        $cart->addProduct(ObjectMother::product('T-shirt', ObjectMother::money(null, 10)));
        $cart->addProduct(ObjectMother::product('T-shirt', ObjectMother::money(null, 10)));
        $cart->addProduct(ObjectMother::product('Shorts', ObjectMother::money(null, 30)));
        $offers_specs = [new NoOffer(), new NoOffer()];
        $offers = $cart->getAvailableOffers($offers_specs);
        $this->assertCount(0, $offers);
    }

    /**
     * @test
     */
    public function canCalculateTotal()
    {
        $cart = new Cart();
        $cart->addProduct(ObjectMother::product('T-shirt', ObjectMother::money(null, 10)));
        $cart->addProduct(ObjectMother::product('T-shirt', ObjectMother::money(null, 10)));
        $cart->addProduct(ObjectMother::product('Shorts', ObjectMother::money(null, 30)));
        $offers = [ObjectMother::constantOffer(ObjectMother::money(null, 5))];
        $tax_calculator = ObjectMother::constantTaxCalculator(10);
        // 50 + 30 taxes - 5 offer
        $total = $cart->getTotal($tax_calculator, $offers);
        $this->assertTrue($total->equals(ObjectMother::money(null, 75)));

    }

}




class NoOffer implements IOfferSpecification{

    public function isValidFor(Cart $cart): bool
    {
        return false;
    }

    public function calculateOfferValue(Cart $cart): Money
    {
        return ObjectMother::money();
    }

    public function getOfferName(): string
    {
        return "";
    }
}
