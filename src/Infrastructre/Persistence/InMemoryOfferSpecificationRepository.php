<?php


namespace Yashry\Infrastructre\Persistence;


use Yashry\Domain\Offer\Service\IOfferSpecification;
use Yashry\Domain\Offer\Service\IOfferSpecificationRepository;

/**
 * Implementation of the offer specification repository inside the memory
 * @package Yashry\Infrastructre\Persistence
 */
class InMemoryOfferSpecificationRepository implements IOfferSpecificationRepository
{

    /** @var IOfferSpecification[] */
    private array $data;

    public function __construct()
    {
        $this->data = [];
    }

    /**
     * @return IOfferSpecification[]
     */
    public function findAll()
    {
        return $this->data;
    }

    public function add(IOfferSpecification $offer_specification)
    {
        $this->data[] = $offer_specification;
    }
}