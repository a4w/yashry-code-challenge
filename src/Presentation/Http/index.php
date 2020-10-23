<?php
// Autoload classes
include_once __DIR__ . './../../../vendor/autoload.php';

use DI\ContainerBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yashry\Application\Service\CreateCartFromProducts;
use Yashry\Domain\Offer\Service\IOfferSpecificationRepository;
use Yashry\Domain\Product\Service\IProductRepository;
use Yashry\Domain\Product\Service\ITaxCalculator;
use Yashry\Domain\Product\Service\RegularTaxCalculator;
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
}catch (Exception $e){
    echo 'Error setting up environment: ' . $e->getMessage();
    exit('shit');
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
