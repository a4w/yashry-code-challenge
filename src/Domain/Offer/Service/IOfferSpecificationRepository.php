<?php


namespace Yashry\Domain\Offer\Service;


interface IOfferSpecificationRepository
{
    /**
     * @return IOfferSpecification[]
     */
    public function findAll();

    public function add(IOfferSpecification $offer_specification);
}