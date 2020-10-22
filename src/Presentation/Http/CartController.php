<?php


namespace Yashry\Presentation\Http;


use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yashry\Application\DataTransferObject\CreateCartFromProductsRequest;
use Yashry\Application\Service\ICreateCartFromProducts;
use Yashry\Presentation\DataTransformers\DefaultTransformer;

class CartController
{
    private ICreateCartFromProducts $create_cart_service;
    public function __construct(ICreateCartFromProducts $create_cart_service)
    {
        $this->create_cart_service = $create_cart_service;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function createCart(Request $request): Response{
        $json = json_decode($request->getContent(), true);
        if(json_last_error() !== JSON_ERROR_NONE){
            throw new Exception('JSON body could not be parsed');
        }
        if(!isset($json['products']) || !is_array($json['products'])){
            throw new Exception('JSON must contain an array with the key \'products\'');
        }
        $request_dto = new CreateCartFromProductsRequest($json['products']);
        $response_dto = $this->create_cart_service->execute($request_dto);
        $json_response = (new DefaultTransformer($response_dto))->toJson();
        return new JsonResponse($json_response, 200, [], true);
    }
}