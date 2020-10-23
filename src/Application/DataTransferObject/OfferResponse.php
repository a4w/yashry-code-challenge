<?php


namespace Yashry\Application\DataTransferObject;


use Yashry\Domain\Offer\Offer;
use Yashry\Domain\ValueObject\Currency;

class OfferResponse
{
    public string $value;
    public string $offer_name;

    public function __construct(Offer $offer, Currency $currency)
    {
        $this->value = '-' . (string)$offer->getDiscountValue()->convertTo($currency);
        $this->offer_name = $offer->getOfferSpecification()->getOfferName();
    }
}