<?php

namespace Yashry\Domain\Cart;

use PHPUnit\Framework\TestCase;
use Yashry\Domain\Product\Service\ITaxCalculator;
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
        $taxes = $cart->getTaxesTotal(new ConstantTaxCalculator(10));
        $this->assertSame(30.0, $taxes->getValue());
    }
}

class ConstantTaxCalculator implements ITaxCalculator{
    private Float $tax;
    public function __construct(Float $value){
        $this->tax = $value;
    }
    public function calculate(Money $for): Money
    {
        return new Money($for->getCurrency(), $this->tax);
    }
}
