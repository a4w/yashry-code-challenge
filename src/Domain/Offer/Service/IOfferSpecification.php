<?php


namespace Yashry\Domain\Offer\Service;


use Yashry\Domain\Cart\Cart;
use Yashry\Domain\ValueObject\Money;

interface IOfferSpecification
{
    public function isValidFor(Cart $cart): Bool;
    public function calculateOfferValue(Cart $cart): Money;
    public function getOfferName(): String;
}