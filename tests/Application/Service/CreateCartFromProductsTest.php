<?php

namespace Application\Service;

use Yashry\Application\DataTransferObject\CreateCartFromProductsRequest;
use Yashry\Application\Service\CreateCartFromProducts;
use PHPUnit\Framework\TestCase;
use Yashry\Infrastructre\Persistence\InMemoryOfferSpecificationRepository;
use Yashry\Infrastructre\Persistence\InMemoryProductRepository;
use Yashry\ObjectMother;
use Exception;

class CreateCartFromProductsTest extends TestCase
{
    /**
     * @test
     */
    public function canHandleNotFoundProducts()
    {
        $this->expectException(Exception::class);
        $product_repository = new InMemoryProductRepository();
        $offer_spec_repository = new InMemoryOfferSpecificationRepository();
        $service = new CreateCartFromProducts($product_repository, $offer_spec_repository, ObjectMother::constantTaxCalculator());
        $dto = new CreateCartFromProductsRequest(['T-shirt', 'Shoes']);
        $service->execute($dto);
    }

    /**
     * @test
     */
    public function canCreateCart()
    {
        $product_repository = new InMemoryProductRepository();
        $product_repository->save(ObjectMother::product('T-shirt', ObjectMother::money(null, 10)));
        $product_repository->save(ObjectMother::product('Shoes', ObjectMother::money(null, 20)));

        $offer_spec_repository = new InMemoryOfferSpecificationRepository();
        $offer_spec_repository->add(ObjectMother::constantOffer(ObjectMother::money(null, 5)));

        $service = new CreateCartFromProducts($product_repository, $offer_spec_repository, ObjectMother::constantTaxCalculator(10));
        $dto = new CreateCartFromProductsRequest(['T-shirt', 'Shoes']);
        $response_dto = $service->execute($dto);
        $this->assertSame("$30", $response_dto->subtotal);
        $this->assertSame("$20", $response_dto->taxes);
        $this->assertCount(1, $response_dto->offers);
        $this->assertSame("$45", $response_dto->total);
    }

}
