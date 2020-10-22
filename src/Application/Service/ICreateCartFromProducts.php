<?php


namespace Yashry\Application\Service;


use Yashry\Application\DataTransferObject\CreateCartFromProductResponse;
use Yashry\Application\DataTransferObject\CreateCartFromProductsRequest;

interface ICreateCartFromProducts
{
    public function execute(CreateCartFromProductsRequest $request): CreateCartFromProductResponse;
}