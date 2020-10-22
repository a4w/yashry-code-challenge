<?php


namespace Yashry\Domain\Cart;


use Yashry\Domain\Offer\Offer;
use Yashry\Domain\Offer\Service\IOfferSpecification;
use Yashry\Domain\Product\Product;
use Yashry\Domain\Product\Service\ITaxCalculator;
use Yashry\Domain\ValueObject\Money;
use Yashry\Domain\ValueObject\MoneyFactory;

class Cart
{
    /** @var CartItem[] */
    private array $items;

    /**
     * Cart constructor.
     */
    public function __construct()
    {
        $this->items = [];
    }

    /**
     * @return CartItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param CartItem[] $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    public function addProduct(Product $product): void
    {
        $exists = false;
        foreach ($this->items as &$item) {
            if ($item->getProduct()->equals($product)) {
                $item->increaseQuantity();
                $exists = true;
            }
        }
        if (!$exists) {
            $this->items[] = new CartItem($product, 1);
        }
    }

    public function getSubtotal(): Money
    {
        $total = MoneyFactory::zero();
        foreach ($this->items as $item) {
            $price = $item->getProduct()->getPrice();
            $line_total = new Money($price->getCurrency(), $price->getValue() * $item->getQuantity());
            $total = $total->add($line_total);
        }
        return $total;
    }

    public function getTaxesTotal(ITaxCalculator $calculator): Money
    {
        $total = MoneyFactory::zero();
        foreach ($this->items as $item) {
            $price = $item->getProduct()->getTax($calculator);
            $line_tax_total = new Money($price->getCurrency(), $price->getValue() * $item->getQuantity());
            $total = $total->add($line_tax_total);
        }
        return $total;
    }

    public function getCartItemForProduct(Product $product): ?CartItem
    {
        foreach ($this->items as &$item) {
            if ($item->getProduct()->equals($product)) {
                return $item;
            }
        }
        return null;
    }

    /**
     * @param IOfferSpecification[] $offers_specs
     * @return Offer[]
     */
    public function getAvailableOffers(array $offers_specs): array
    {
        $offers = [];
        foreach ($offers_specs as $spec) {
            if ($spec->isValidFor($this)) {
                $value = $spec->calculateOfferValue($this);
                $offers[] = new Offer($spec, $value);
            }
        }
        return $offers;
    }

}