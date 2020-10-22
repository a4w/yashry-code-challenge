<?php

namespace Presentation\Http;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Yashry\Application\DataTransferObject\CreateCartFromProductResponse;
use Yashry\Application\DataTransferObject\CreateCartFromProductsRequest;
use Yashry\Application\Service\ICreateCartFromProducts;
use Yashry\Domain\Cart\Cart;
use Yashry\ObjectMother;
use Yashry\Presentation\Http\CartController;
use PHPUnit\Framework\TestCase;

class CartControllerTest extends TestCase
{
    /**
     * @test
     */
    public function rejectsEmptyRequests()
    {
        $this->expectException(Exception::class);
        $request = new Request([],[],[],[],[],[], "");
        $controller = new CartController(new FakeCreateCartService());
        $controller->createCart($request);
    }

    /**
     * @test
     */
    public function rejectsMalformedRequests()
    {
        $this->expectException(Exception::class);
        $request = new Request([],[],[],[],[],[], json_encode(['products' => true]));
        $controller = new CartController(new FakeCreateCartService());
        $controller->createCart($request);
    }
    /**
     * @test
     */
    public function acceptsValidRequests()
    {
        $request = new Request([],[],[],[],[],[], json_encode(['products' => ['T-shirt', 'Shoes']]));
        $controller = new CartController(new FakeCreateCartService());
        $response = $controller->createCart($request);
        $response = $response->getContent();
        $this->assertJson($response);
    }

}

class FakeCreateCartService implements ICreateCartFromProducts {

    public function execute(CreateCartFromProductsRequest $request): CreateCartFromProductResponse
    {
        return new CreateCartFromProductResponse(new Cart(), ObjectMother::constantTaxCalculator(), []);
    }
}
