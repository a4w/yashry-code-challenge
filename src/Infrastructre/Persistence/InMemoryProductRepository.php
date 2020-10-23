<?php


namespace Yashry\Infrastructre\Persistence;


use Yashry\Domain\Product\Product;
use Yashry\Domain\Product\Service\IProductRepository;

/**
 * Implementation of the products repository inside the memory
 * @package Yashry\Infrastructre\Persistence
 */
class InMemoryProductRepository implements IProductRepository
{

    /** @var Product[] */
    private array $data;

    public function findByTitle(string $name): ?Product
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
        return null;
    }

    public function save(Product $product)
    {
        $this->data[$product->getTitle()] = $product;
    }

}