<?php
// Autoload classes
include_once __DIR__ . './../../../vendor/autoload.php';

use DI\ContainerBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yashry\Application\Service\CreateCartFromProducts;
use Yashry\Domain\Offer\Service\BuyXProductGetYPercentOffProduct;
use Yashry\Domain\Offer\Service\IOfferSpecificationRepository;
use Yashry\Domain\Offer\Service\ProductDiscountedPercent;
use Yashry\Domain\Product\Product;
use Yashry\Domain\Product\Service\IProductRepository;
use Yashry\Domain\Product\Service\ITaxCalculator;
use Yashry\Domain\Product\Service\RegularTaxCalculator;
use Yashry\Domain\ValueObject\Currency;
use Yashry\Domain\ValueObject\Money;
use Yashry\Infrastructre\Persistence\InMemoryOfferSpecificationRepository;
use Yashry\Infrastructre\Persistence\InMemoryProductRepository;
use Yashry\Presentation\Http\CartController;
use function DI\create;

// Setup dependency injection
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
    IProductRepository::class => create(InMemoryProductRepository::class),
    IOfferSpecificationRepository::class => create(InMemoryOfferSpecificationRepository::class),
    ITaxCalculator::class => create(RegularTaxCalculator::class)
]);
try{
    $container = $containerBuilder->build();

    // Hydrate repositories for testing
    $products_repository = $container->get(IProductRepository::class);
    $offer_specs_repository = $container->get(IOfferSpecificationRepository::class);

    // Add products
    $default_currency = new Currency();
    $t_shirts = new Product('T-shirt', new Money($default_currency, 10.99));
    $pants = new Product('Pants', new Money($default_currency, 14.99));
    $jacket = new Product('Jacket', new Money($default_currency, 19.99));
    $shoes = new Product('Shoes', new Money($default_currency, 24.99));
    $products_repository->save($t_shirts);
    $products_repository->save($pants);
    $products_repository->save($jacket);
    $products_repository->save($shoes);

    // Add offers
    $offer_specs_repository->add(new ProductDiscountedPercent("10% off shoes", $shoes, 10));
    $offer_specs_repository->add(new BuyXProductGetYPercentOffProduct("50% off jacket", $t_shirts, 2, $jacket, 50));

}catch (Exception $e){
    echo 'Error setting up environment: ' . $e->getMessage();
}



// Very simple naive routing as I didn't want to use a framework here and implementing fully fledged routing is an over kill
$response = new Response();
try{
    $request = Request::createFromGlobals();
    if($request->getMethod() === 'POST' && $request->getPathInfo() === '/cart'){
        $controller = new CartController($container->get(CreateCartFromProducts::class));
        $response = $controller->createCart($request);
    }
}catch (Exception $e){
    $response = new JsonResponse([
        'error' => true,
        'message' => $e->getMessage(),
        'code' => $e->getCode()
    ], 200);
}
$response->send();
