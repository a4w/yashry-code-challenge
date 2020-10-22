<?php


namespace Yashry\Application\DataTransferObject;


use Yashry\Domain\Cart\Cart;
use Yashry\Domain\Offer\Service\IOfferSpecification;
use Yashry\Domain\Product\Service\ITaxCalculator;

class CreateCartFromProductResponse
{
    public String $subtotal;
    public String $taxes;
    /** @var OfferResponse[]  */
    public array $offers;
    public String $total;

    /**
     * CreateCartFromProductResponse constructor.
     * @param Cart $cart
     * @param ITaxCalculator $tax_calculator
     * @param IOfferSpecification[] $available_offers
     */
    public function __construct(Cart $cart, ITaxCalculator $tax_calculator, array $available_offers)
    {
        $this->subtotal = (string) $cart->getSubtotal();
        $this->taxes = (string) $cart->getTaxesTotal($tax_calculator);
        $this->offers = array_map(function($offer){
            return new OfferResponse($offer);
        }, $cart->getAvailableOffers($available_offers));
        $this->total = (string) $cart->getTotal($tax_calculator, $available_offers);
    }
}