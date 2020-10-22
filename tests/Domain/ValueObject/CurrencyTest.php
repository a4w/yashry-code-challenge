<?php
namespace Domain\ValueObject;

use InvalidArgumentException;
use Yashry\Domain\ValueObject\Currency;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    /**
     * @test
     */
    public function currencyCantHaveInvalidISO(){
        $this->expectException(InvalidArgumentException::class);
        new Currency('usdd', '$', 1);
    }

    /**
     * @test
     */
    public function currencyCantHaveEmptySymbol(){
        $this->expectException(InvalidArgumentException::class);
        new Currency('USD', '', 1);
    }

    /**
     * @test
     */
    public function currencyCantHaveNonPositiveConversionRate(){
        $this->expectException(InvalidArgumentException::class);
        new Currency('USD', '$', 0);
    }

    /**
     * @test
     */
    public function currencyCanBeCreated(){
        $currency = new Currency('USD', '$', 1);
        $this->assertInstanceOf(Currency::class, $currency);
    }
}
