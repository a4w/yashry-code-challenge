<?php


namespace Yashry\Domain\Offer\Service;


interface IOfferSpecificationRepository
{
    /**
     * @return IOfferSpecification[]
     */
    public function findAll();
}