<?php

namespace FmTod\Money\Tests\Feature;

use FmTod\Money\Formatters\CurrencySymbolMoneyFormatter;
use FmTod\Money\Formatters\DecimalMoneyFormatter;
use FmTod\Money\Money;
use FmTod\Money\Tests\TestCase;
use InvalidArgumentException;
use Money\Currencies\ISOCurrencies;
use NumberFormatter;
use stdClass;

class CustomMoneyFormatterTest extends TestCase
{
    public function test_currency_symbol_money_formatter()
    {
        config()->set('money.formatter', CurrencySymbolMoneyFormatter::class);

        static::assertEquals('$1.00', Money::USD(100)->format());
        static::assertEquals('€1.00', Money::EUR(100)->format());
        static::assertEquals('€1.00', Money::EUR(100)->format('en_US'));
        static::assertEquals('$1.00', Money::USD(100)->format('en_US', Money::getCurrencies()));
        static::assertEquals('€1.99', Money::EUR(199)->format('fr_FR', Money::getCurrencies(), NumberFormatter::DECIMAL));
        static::assertEquals('$1.00', Money::USD(100)->format('en_US', Money::getCurrencies(), NumberFormatter::DECIMAL));
    }

    public function test_decimal_money_formatter()
    {
        config()->set('money.formatter', DecimalMoneyFormatter::class);

        static::assertEquals('1.00', Money::USD(100)->format());
        static::assertEquals('-1.00', Money::USD(-100)->format());
        static::assertEquals('0.01', Money::USD(1)->format());
        static::assertEquals('1.00', Money::EUR(100)->format());
        static::assertEquals('1.00', Money::EUR(100)->format('en_US'));
        static::assertEquals('1.00', Money::USD(100)->format('en_US', Money::getCurrencies()));
        static::assertEquals('1.99', Money::EUR(199)->format('fr_FR', Money::getCurrencies(), NumberFormatter::DECIMAL));
        static::assertEquals('1.00', Money::USD(100)->format('en_US', Money::getCurrencies(), NumberFormatter::DECIMAL));
    }

    public function test_decimal_money_formatter_with_args()
    {
        config()->set('money.formatter', [DecimalMoneyFormatter::class, [new ISOCurrencies]]);

        static::assertEquals('1.00', Money::USD(100)->format());
        static::assertEquals('1.00', Money::EUR(100)->format());
        static::assertEquals('1.00', Money::EUR(100)->format('en_US'));
        static::assertEquals('1.00', Money::USD(100)->format('en_US', Money::getCurrencies()));
        static::assertEquals('1.99', Money::EUR(199)->format('fr_FR', Money::getCurrencies(), NumberFormatter::DECIMAL));
        static::assertEquals('1.00', Money::USD(100)->format('en_US', Money::getCurrencies(), NumberFormatter::DECIMAL));
    }

    public function test_invalid_money_formatter()
    {
        config()->set('money.formatter', stdClass::class);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid default formatter '.stdClass::class);

        static::assertEquals('1.00', Money::USD(100)->format());
    }
}
