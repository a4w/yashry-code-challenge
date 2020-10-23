<?php


namespace Yashry\Domain\Offer\Service;


/**
 * Represents the repository of all available offer specifications
 * @package Yashry\Domain\Offer\Service
 */
interface IOfferSpecificationRepository
{
    /**
     * @return IOfferSpecification[]
     */
    public function findAll();

    public function add(IOfferSpecification $offer_specification);
}