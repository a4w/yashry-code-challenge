<?php


namespace Yashry\Domain\Offer;


use Yashry\Domain\Offer\Service\IOfferSpecification;
use Yashry\Domain\ValueObject\Money;

class Offer
{
    private IOfferSpecification $offer_specification;
    private Money $discount_value;

    /**
     * Offer constructor.
     * @param IOfferSpecification $offer_specification
     * @param Money $discount_value
     */
    public function __construct(IOfferSpecification $offer_specification, Money $discount_value)
    {
        $this->offer_specification = $offer_specification;
        $this->discount_value = $discount_value;
    }

    /**
     * @return IOfferSpecification
     */
    public function getOfferSpecification(): IOfferSpecification
    {
        return $this->offer_specification;
    }

    /**
     * @return Money
     */
    public function getDiscountValue(): Money
    {
        return $this->discount_value;
    }

}