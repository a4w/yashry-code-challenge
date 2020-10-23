<?php


namespace Yashry\Domain\ValueObject;


/**
 * Currency repository interface
 * @package Yashry\Domain\ValueObject
 */
interface ICurrencyRepository
{
    public function findByCode(String $code): Currency;
    public function add(Currency $currency);
}