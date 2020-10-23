<?php


namespace Yashry\Application\DataTransferObject;


use Yashry\Domain\Offer\Offer;

class OfferResponse
{
    public string $value;
    public string $offer_name;

    public function __construct(Offer $offer)
    {
        $this->value = '-' . (string)$offer->getDiscountValue();
        $this->offer_name = $offer->getOfferSpecification()->getOfferName();
    }
}