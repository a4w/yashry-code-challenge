<?php


namespace Yashry\Infrastructre\Persistence;


use Yashry\Domain\Offer\Service\IOfferSpecification;
use Yashry\Domain\Offer\Service\IOfferSpecificationRepository;

class InMemoryOfferSpecificationRepository implements IOfferSpecificationRepository
{

    /** @var IOfferSpecification[]  */
    private array $data;

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