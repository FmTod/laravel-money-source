<?php

namespace FmTod\Money\Tests\Feature;

use FmTod\Money\Money;
use FmTod\Money\Tests\TestCase;
use Money\Currency;

class MoneyFactoryTest extends TestCase
{
    public function test_factory_methods()
    {
        $moneyUSD = Money::USD(10);
        static::assertInstanceOf(Money::class, $moneyUSD);
        static::assertEquals(new Money(10, new Currency('USD')), $moneyUSD);

        $moneyEUR = Money::EUR(10);
        static::assertInstanceOf(Money::class, $moneyEUR);
        static::assertEquals(new Money(10, new Currency('EUR')), $moneyEUR);

        $moneyBRL = Money::BRL(10);
        static::assertInstanceOf(Money::class, $moneyBRL);
        static::assertEquals(new Money(10, new Currency('BRL')), $moneyBRL);
    }
}
