<?php


namespace Yashry\Application\Service;


use Exception;
use Yashry\Application\DataTransferObject\CreateCartFromProductResponse;
use Yashry\Application\DataTransferObject\CreateCartFromProductsRequest;
use Yashry\Domain\Cart\CartFactory;
use Yashry\Domain\Offer\Service\IOfferSpecificationRepository;
use Yashry\Domain\Product\Service\IProductRepository;
use Yashry\Domain\Product\Service\ITaxCalculator;

class CreateCartFromProducts implements ICreateCartFromProducts
{
    private IProductRepository $product_repository;
    private IOfferSpecificationRepository $offer_specification_repository;
    private ITaxCalculator $tax_calculator;

    public function __construct(
        IProductRepository $product_repository,
        IOfferSpecificationRepository $offer_specification_repository,
        ITaxCalculator $tax_calculator
    )
    {
        $this->product_repository = $product_repository;
        $this->offer_specification_repository = $offer_specification_repository;
        $this->tax_calculator = $tax_calculator;
    }

    /**
     * @param CreateCartFromProductsRequest $request
     * @return CreateCartFromProductResponse
     * @throws Exception
     */
    public function execute(CreateCartFromProductsRequest $request): CreateCartFromProductResponse
    {
        $product_names = $request->products;
        $products = [];
        foreach ($product_names as $product_name) {
            $product = $this->product_repository->findByTitle($product_name);
            if ($product === null) {
                throw new Exception("Product with name {$product_name} was not found");
            }
            $products[] = $product;
        }
        $cart = CartFactory::createFromProducts($products);
        $available_offers = $this->offer_specification_repository->findAll();
        return new CreateCartFromProductResponse($cart, $this->tax_calculator, $available_offers);
    }
}