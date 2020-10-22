<?php


namespace Yashry\Domain\Product\Service;


use Yashry\Domain\Product\Product;

interface IProductRepository
{
    public function findByTitle(String $name): ?Product;
    public function save(Product $product);
}