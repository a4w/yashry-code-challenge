<?php


namespace Yashry\Application\DataTransferObject;


class CreateCartFromProductsRequest
{
    /** @var String[]  */
    public array $products;

    /**
     * @param String[]  $products
     */
    public function __construct(array $products)
    {
        $this->products = $products;
    }
}