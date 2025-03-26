<?php

namespace FmTod\Money\Tests\Feature;

use FmTod\Money\Money;
use FmTod\Money\Tests\TestCase;
use Money\Currency;

use function currency;
use function money;
use function money_avg;
use function money_max;
use function money_min;
use function money_parse;
use function money_parse_by_bitcoin;
use function money_parse_by_decimal;
use function money_parse_by_intl;
use function money_parse_by_intl_localized_decimal;
use function money_sum;

class HelpersTest extends TestCase
{
    public function test_currency()
    {
        static::assertEquals(money(25), new Money(25, new Currency('USD')));
        static::assertEquals(money(25, 'USD'), new Money(25, new Currency('USD')));
        static::assertEquals(money(25, 'EUR'), new Money(25, new Currency('EUR')));
    }

    public function test_money()
    {
        static::assertEquals(currency('USD'), new Currency('USD'));
        static::assertEquals(currency('EUR'), new Currency('EUR'));
    }

    public function test_money_min()
    {
        static::assertEquals(money_min(Money::USD(10), Money::USD(20), Money::USD(30)), Money::USD(10));
        static::assertEquals(money_min(Money::EUR(10), Money::EUR(20), Money::EUR(30)), Money::EUR(10));
    }

    public function test_money_max()
    {
        static::assertEquals(money_max(Money::USD(10), Money::USD(20), Money::USD(30)), Money::USD(30));
        static::assertEquals(money_max(Money::EUR(10), Money::EUR(20), Money::EUR(30)), Money::EUR(30));
    }

    public function test_money_avg()
    {
        static::assertEquals(money_avg(Money::USD(10), Money::USD(20), Money::USD(30)), Money::USD(20));
        static::assertEquals(money_avg(Money::EUR(10), Money::EUR(20), Money::EUR(30)), Money::EUR(20));
    }

    public function test_money_sum()
    {
        static::assertEquals(money_sum(Money::USD(10), Money::USD(20), Money::USD(30)), Money::USD(60));
        static::assertEquals(money_sum(Money::EUR(10), Money::EUR(20), Money::EUR(30)), Money::EUR(60));
    }

    public function test_money_parse()
    {
        static::assertEquals(money_parse('$1.00'), Money::USD(100));
        static::assertEquals(money_parse('$1.00', 'USD'), Money::USD(100));
    }

    public function test_money_parse_by_bitcoin()
    {
        static::assertEquals(money_parse_by_bitcoin("\xC9\x831000.00"), Money::XBT(100000));
        static::assertEquals(money_parse_by_bitcoin("\xC9\x831000.00", null, 4), Money::XBT(10000000));
    }

    public function test_money_parse_by_decimal()
    {
        static::assertEquals(money_parse_by_decimal('5.00', 'EUR'), Money::EUR(500));
        static::assertEquals(money_parse_by_decimal('5.00', 'USD', null), Money::USD(500));
    }

    public function test_money_parse_intl()
    {
        static::assertEquals(money_parse_by_intl('$1.00'), Money::USD(100));
        static::assertEquals(money_parse_by_intl('$1.00', 'EUR'), Money::EUR(100));
        static::assertEquals(money_parse_by_intl('$1.00', 'USD', 'en_US'), Money::USD(100));
        static::assertEquals(money_parse_by_intl('$1.00', 'USD', 'en_US', Money::getCurrencies()), Money::USD(100));
    }

    public function test_money_parse_intl_localized_decimal()
    {
        static::assertEquals(money_parse_by_intl_localized_decimal('1.00', 'USD'), Money::USD(100));
        static::assertEquals(money_parse_by_intl_localized_decimal('1.00', 'EUR'), Money::EUR(100));
        static::assertEquals(money_parse_by_intl_localized_decimal('1.00', 'USD', 'en_US'), Money::USD(100));
        static::assertEquals(
            money_parse_by_intl_localized_decimal('1.00', 'USD', 'en_US', Money::getCurrencies()),
            Money::USD(100)
        );
    }
}
