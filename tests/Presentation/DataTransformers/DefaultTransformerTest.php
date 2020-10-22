<?php

namespace Presentation\DataTransformers;

use Yashry\Application\DataTransferObject\CreateCartFromProductResponse;
use Yashry\Domain\Cart\Cart;
use Yashry\ObjectMother;
use Yashry\Presentation\DataTransformers\DefaultTransformer;
use PHPUnit\Framework\TestCase;

class DefaultTransformerTest extends TestCase
{
    /**
     * @test
     */
    public function testJsonTransformer()
    {
        $offers = [ObjectMother::constantOffer()];
        $dto = new CreateCartFromProductResponse(new Cart(), ObjectMother::constantTaxCalculator(), $offers);
        $transformer = new DefaultTransformer($dto);
        $raw_json = $transformer->toJson();
        $this->assertJson($raw_json);
    }

}
