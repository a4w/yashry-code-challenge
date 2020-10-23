<?php


namespace Yashry\Domain\Offer\Service;


use Yashry\Domain\Cart\Cart;
use Yashry\Domain\ValueObject\Money;

/**
 * Represents an abstract offer available for a cart
 * @package Yashry\Domain\Offer\Service
 */
interface IOfferSpecification
{
    public function isValidFor(Cart $cart): bool;

    public function calculateOfferValue(Cart $cart): Money;

    public function getOfferName(): string;
}