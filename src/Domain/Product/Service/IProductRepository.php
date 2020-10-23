<?php


namespace Yashry\Domain\Product\Service;


use Yashry\Domain\Product\Product;

/**
 * Represents a repository of products
 * @package Yashry\Domain\Product\Service
 */
interface IProductRepository
{
    public function findByTitle(string $name): ?Product;

    public function save(Product $product);
}