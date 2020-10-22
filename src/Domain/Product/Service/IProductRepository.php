<?php


namespace Yashry\Domain\Product\Service;


use Yashry\Domain\Product\Product;

interface IProductRepository
{
    public function findByName(String $name): Product;
}