<?php

namespace FmTod\Money\Tests\Feature;

use FmTod\Money\Tests\TestCase;
use FmTod\Money\Traits\CurrenciesTrait;
use Money\Currency;
use stdClass;

class CurrenciesTraitTest extends TestCase
{
    private static function currencyListSize(\Money\Currencies $currencies)
    {
        $size = 0;
        foreach ($currencies as $currency) {
            $size++;
        }

        return $size;
    }

    public function test_get_currencies()
    {
        $mock = $this->getMockForTrait(CurrenciesTrait::class);

        static::assertInstanceOf(\Money\Currencies::class, $mock->getCurrencies());
        static::assertInstanceOf(\Money\Currencies\AggregateCurrencies::class, $mock->getCurrencies());
    }

    public function test_set_currencies()
    {
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies(new \Money\Currencies\BitcoinCurrencies);

        static::assertInstanceOf(\Money\Currencies::class, $mock->getCurrencies());
        static::assertInstanceOf(\Money\Currencies\BitcoinCurrencies::class, $mock->getCurrencies());
    }

    public function test_set_currencies_custom_currencies()
    {
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies([
            'iso' => ['USD'],
            'bitcoin' => ['XBT'],
            'custom' => [
                'MY1' => 2,
                'MY2' => 3,
            ],
        ]);

        static::assertInstanceOf(\Money\Currencies\AggregateCurrencies::class, $mock->getCurrencies());
        static::assertContainsOnlyInstancesOf(Currency::class, $mock->getCurrencies());
        static::assertEquals(4, static::currencyListSize($mock->getCurrencies()));
        static::assertTrue($mock->getCurrencies()->contains(new Currency('USD')));
        static::assertTrue($mock->getCurrencies()->contains(new Currency('XBT')));
        static::assertTrue($mock->getCurrencies()->contains(new Currency('MY1')));
        static::assertTrue($mock->getCurrencies()->contains(new Currency('MY2')));
        static::assertEquals(2, $mock->getCurrencies()->subunitFor(new Currency('MY1')));
        static::assertEquals(3, $mock->getCurrencies()->subunitFor(new Currency('MY2')));
    }

    public function test_set_currencies_wrong_iso_currency_code()
    {
        $this->expectException(\InvalidArgumentException::class);
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies([
            'iso' => ['UNKNOWN'],
            'bitcoin' => ['XBT'],
            'custom' => [
                'MY1' => 2,
                'MY2' => 3,
            ],
        ]);
    }

    public function test_set_currencies_wrong_bitcoin_currency_code()
    {
        $this->expectException(\InvalidArgumentException::class);
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies([
            'iso' => ['USD'],
            'bitcoin' => ['UNKNOWN'],
            'custom' => [
                'MY1' => 2,
                'MY2' => 3,
            ],
        ]);
    }

    public function test_set_currencies_wrong_standard_currencies_config()
    {
        $this->expectException(\InvalidArgumentException::class);
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies([
            'iso' => new stdClass,
            'bitcoin' => 'all',
        ]);
    }

    public function test_set_currencies_empty_currencies_config()
    {
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies([
            'iso' => [],
            'bitcoin' => [],
            'custom' => [],
        ]);

        static::assertInstanceOf(\Money\Currencies::class, $mock->getCurrencies());
        static::assertEquals(0, static::currencyListSize($mock->getCurrencies()));
    }

    public function test_set_currencies_default_currencies_config()
    {
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies([]);

        static::assertInstanceOf(\Money\Currencies\ISOCurrencies::class, $mock->getCurrencies());
    }

    public function test_set_currencies_all_iso_currencies()
    {
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies([
            'iso' => 'all',
        ]);

        static::assertEquals(
            new \Money\Currencies\AggregateCurrencies([new \Money\Currencies\ISOCurrencies]),
            $mock->getCurrencies()
        );
    }

    public function test_set_currencies_all_bitcoin_currencies()
    {
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies([
            'bitcoin' => 'all',
        ]);

        static::assertEquals(
            new \Money\Currencies\AggregateCurrencies([new \Money\Currencies\BitcoinCurrencies]),
            $mock->getCurrencies()
        );
    }
}
