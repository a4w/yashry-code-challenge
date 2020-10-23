<?php


namespace Yashry\Domain\Product;


use InvalidArgumentException;
use Yashry\Domain\Product\Service\ITaxCalculator;
use Yashry\Domain\ValueObject\Money;

/**
 * Represents a product with a unique title and a price
 * @package Yashry\Domain\Product
 */
class Product
{
    private string $title;
    private Money $price;

    /**
     * Product constructor.
     * @param String $title
     * @param Money $price
     */
    public function __construct(string $title, Money $price)
    {
        $this->title = $title;
        $this->price = $price;
    }

    /**
     * @return String
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param String $title
     */
    public function setTitle(string $title): void
    {
        if (strlen($title) === 0) {
            throw new InvalidArgumentException('Product title cannot be empty');
        }
        $this->title = $title;
    }

    /**
     * @return Money
     */
    public function getPrice(): Money
    {
        return $this->price;
    }

    /**
     * @param Money $price
     */
    public function setPrice(Money $price): void
    {
        $this->price = $price;
    }

    /**
     * @param ITaxCalculator $calculator
     * @return Money
     */
    public function getTax(ITaxCalculator $calculator): Money
    {
        return $calculator->calculate($this);
    }

    public function equals(Product $product)
    {
        // I'm assuming the title is the unique identifier for this business
        return $this->getTitle() === $product->getTitle();
    }

}