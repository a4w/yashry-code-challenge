<?php


namespace Yashry\Application\DataTransferObject;


class CreateCartFromProductsRequest
{
    /** @var String[] */
    public array $products;
    public string $currency_code;

    /**
     * @param String[] $products
     * @param string $currency_code
     */
    public function __construct(array $products, string $currency_code)
    {
        $this->products = $products;
        $this->currency_code = $currency_code;
    }
}