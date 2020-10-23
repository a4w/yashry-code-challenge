<?php


namespace Yashry\Application\Service;


use Exception;
use Yashry\Application\DataTransferObject\CreateCartFromProductResponse;
use Yashry\Application\DataTransferObject\CreateCartFromProductsRequest;
use Yashry\Domain\Cart\CartFactory;
use Yashry\Domain\Offer\Service\IOfferSpecificationRepository;
use Yashry\Domain\Product\Service\IProductRepository;
use Yashry\Domain\Product\Service\ITaxCalculator;
use Yashry\Domain\ValueObject\ICurrencyRepository;

/**
 * Application service class the coordinates the creation of carts from products
 * @package Yashry\Application\Service
 */
class CreateCartFromProducts implements ICreateCartFromProducts
{
    private IProductRepository $product_repository;
    private IOfferSpecificationRepository $offer_specification_repository;
    private ITaxCalculator $tax_calculator;
    private ICurrencyRepository $currency_repository;

    public function __construct(
        IProductRepository $product_repository,
        IOfferSpecificationRepository $offer_specification_repository,
        ICurrencyRepository $currency_repository,
        ITaxCalculator $tax_calculator
    )
    {
        $this->product_repository = $product_repository;
        $this->offer_specification_repository = $offer_specification_repository;
        $this->tax_calculator = $tax_calculator;
        $this->currency_repository = $currency_repository;
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
        // Check that all products supplied exists
        foreach ($product_names as $product_name) {
            $product = $this->product_repository->findByTitle($product_name);
            if ($product === null) {
                throw new Exception("Product with name {$product_name} was not found");
            }
            $products[] = $product;
        }
        // Check required currency exists
        $currency = $this->currency_repository->findByCode($request->currency_code);
        if($currency === null){
            throw new Exception("Currency with code {$request->currency_code} was not found");
        }
        // Create the cart aggregate root
        $cart = CartFactory::createFromProducts($products);
        // Get all offers that may apply to the cart
        $available_offers = $this->offer_specification_repository->findAll();

        // Return the DTO representing the response, passing the domain object. A better approach here would be to use a DTO Assembler
        // But for the sake of this application this is fine as logic is delegated to the service anyway
        return new CreateCartFromProductResponse($cart, $currency, $this->tax_calculator, $available_offers);
    }
}