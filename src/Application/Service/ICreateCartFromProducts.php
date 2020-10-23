<?php


namespace Yashry\Application\Service;


use Yashry\Application\DataTransferObject\CreateCartFromProductResponse;
use Yashry\Application\DataTransferObject\CreateCartFromProductsRequest;

/**
 * Application service interface
 * @package Yashry\Application\Service
 */
interface ICreateCartFromProducts
{
    public function execute(CreateCartFromProductsRequest $request): CreateCartFromProductResponse;
}