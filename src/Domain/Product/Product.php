<?php


namespace Yashry\Domain\Product;


use Yashry\Domain\ValueObject\Money;

class Product
{
    private String $title;
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

}