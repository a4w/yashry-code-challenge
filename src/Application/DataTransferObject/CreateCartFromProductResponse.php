<?php


namespace Yashry\Application\DataTransferObject;


use Yashry\Domain\Cart\Cart;
use Yashry\Domain\Offer\Service\IOfferSpecification;
use Yashry\Domain\Product\Service\ITaxCalculator;
use Yashry\Domain\ValueObject\Currency;

class CreateCartFromProductResponse
{
    public string $subtotal;
    public string $taxes;
    /** @var OfferResponse[] */
    public array $offers;
    public string $total;

    /**
     * CreateCartFromProductResponse constructor.
     * @param Cart $cart
     * @param Currency $currency
     * @param ITaxCalculator $tax_calculator
     * @param IOfferSpecification[] $available_offers
     */
    public function __construct(Cart $cart, Currency $currency, ITaxCalculator $tax_calculator, array $available_offers)
    {
        $this->subtotal = (string)$cart->getSubtotal()->convertTo($currency);
        $this->taxes = (string)$cart->getTaxesTotal($tax_calculator)->convertTo($currency);
        $this->offers = array_map(function ($offer) use($currency) {
            return new OfferResponse($offer, $currency);
        }, $cart->getAvailableOffers($available_offers));
        $this->total = (string)$cart->getTotal($tax_calculator, $available_offers)->convertTo($currency);
    }
}