<?php


namespace Yashry\Application\DataTransferObject;


use Yashry\Domain\Offer\Offer;

class OfferResponse
{
    public String $value;
    public String $offer_name;

    public function __construct(Offer $offer)
    {
        $this->value = $offer->getDiscountValue();
        $this->offer_name = $offer->getOfferSpecification()->getOfferName();
    }
}