<?php


namespace Yashry\Presentation\Http;


use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yashry\Application\DataTransferObject\CreateCartFromProductsRequest;
use Yashry\Application\Service\ICreateCartFromProducts;
use Yashry\Presentation\DataTransformers\DefaultTransformer;

/**
 * Classic controller that returns JSON output (RESTful endpoint)
 * @package Yashry\Presentation\Http
 */
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
    public function createCart(Request $request): Response
    {
        // Read request body
        $json = json_decode($request->getContent(), true);
        // Check that the body is valid JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON body could not be parsed');
        }
        // Check the the json body contains an array of product names
        if (!isset($json['products']) || !is_array($json['products'])) {
            throw new Exception('JSON must contain an array with the key \'products\'');
        }
        // Create DTO request for communicating with the application service (injected to this controller)
        $request_dto = new CreateCartFromProductsRequest($json['products']);
        // Read DTO returned by the appliaton service
        $response_dto = $this->create_cart_service->execute($request_dto);
        // Transform DTO to JSON format to be a consumable API
        $json_response = (new DefaultTransformer($response_dto))->toJson();
        // Return the JSON to continue the request/response http cycle
        return new JsonResponse($json_response, 200, [], true);
    }
}