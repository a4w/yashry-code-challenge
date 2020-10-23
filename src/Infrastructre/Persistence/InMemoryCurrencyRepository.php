<?php


namespace Yashry\Infrastructre\Persistence;


use Yashry\Domain\ValueObject\Currency;
use Yashry\Domain\ValueObject\ICurrencyRepository;

class InMemoryCurrencyRepository implements ICurrencyRepository
{

    /** @var Currency[] */
    private array $data;

    public function __construct()
    {
        $this->data = [];
    }

    /**
     * @param Currency $currency
     */
    public function save(Currency $currency)
    {
        $this->data[$currency->getCurrencyCode()] = $currency;
    }

    /**
     * @param string $code
     * @return Currency|null
     */
    public function findByCode(string $code): ?Currency
    {
        if (isset($this->data[$code])) {
            return $this->data[$code];
        }
        return null;
    }
}