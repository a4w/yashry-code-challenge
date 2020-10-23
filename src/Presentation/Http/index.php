<?php
$di_container = include __DIR__ . "/../../../bootstrap.php";

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yashry\Application\Service\CreateCartFromProducts;
use Yashry\Presentation\Http\CartController;

// Very simple naive routing as I didn't want to use a framework here and implementing fully fledged routing is an over kill
$response = new Response();
try{
    $request = Request::createFromGlobals();
    if($request->getMethod() === 'POST' && $request->getPathInfo() === '/cart'){
        $controller = new CartController($di_container->get(CreateCartFromProducts::class));
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
