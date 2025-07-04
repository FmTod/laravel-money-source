<?php

namespace FmTod\Money\Tests\Feature;

use FmTod\Money\Money;
use Money\Currency;
use Money\Parser\BitcoinMoneyParser;
use Money\Parser\DecimalMoneyParser;
use Money\Parser\IntlMoneyParser;
use NumberFormatter;
use PHPUnit\Framework\TestCase;

class MoneyParserTraitTest extends TestCase
{
    public function test_parse()
    {
        static::assertEquals(Money::parse('$1.00'), Money::USD(100));
        static::assertEquals(Money::parse('$1.00', 'USD'), Money::USD(100));
    }

    public function test_parse_by_aggregate()
    {
        $parsers = [
            new BitcoinMoneyParser(2),
            new DecimalMoneyParser(Money::getCurrencies()),
            new IntlMoneyParser(
                new NumberFormatter(Money::getLocale(), NumberFormatter::CURRENCY),
                Money::getCurrencies()
            ),
        ];

        // static::assertEquals(Money::parseByAggregate("\xC9\x831000.00", 'EUR', $parsers), Money::XBT(100000));
        static::assertEquals(Money::parseByAggregate('1.00', 'EUR', $parsers), Money::EUR(100));
        static::assertEquals(Money::parseByAggregate('$1.00', 'EUR', $parsers), Money::EUR(100));
    }

    public function test_parse_by_bitcoin()
    {
        static::assertEquals(Money::parseByBitcoin("\xC9\x831000.00"), Money::XBT(100000));
        static::assertEquals(Money::parseByBitcoin("-\xC9\x831"), Money::XBT(-100));
        static::assertEquals(Money::parseByBitcoin("\xC9\x831000.00", null, 4), Money::XBT(10000000));
    }

    public function test_parse_by_decimal()
    {
        static::assertEquals(Money::parseByDecimal('1.00', 'EUR'), Money::EUR(100));
        static::assertEquals(Money::parseByDecimal('1.00', 'USD', Money::getCurrencies()), Money::USD(100));
    }

    public function test_parse_intl()
    {
        static::assertEquals(Money::parseByIntl('$1.00'), Money::USD(100));
        static::assertEquals(Money::parseByIntl('$1.00', 'EUR'), Money::EUR(100));
        static::assertEquals(Money::parseByIntl('$1.00', 'USD', 'en_US'), Money::USD(100));
        static::assertEquals(Money::parseByIntl('$1.00', 'USD', 'en_US', Money::getCurrencies()), Money::USD(100));
    }

    public function test_parse_intl_localized_decimal()
    {
        static::assertEquals(Money::parseByIntlLocalizedDecimal('1.00', 'USD'), Money::USD(100));
        static::assertEquals(Money::parseByIntlLocalizedDecimal('1.00', 'EUR'), Money::EUR(100));
        static::assertEquals(Money::parseByIntlLocalizedDecimal('1.00', 'USD', 'en_US'), Money::USD(100));
        static::assertEquals(
            Money::parseByIntlLocalizedDecimal('1.00', 'USD', 'en_US', Money::getCurrencies()),
            Money::USD(100)
        );
    }

    public function test_parse_by_parser()
    {
        $parser = new DecimalMoneyParser(Money::getCurrencies());

        static::assertEquals(Money::parseByParser($parser, '1.00', 'USD'), Money::USD(100));
        static::assertEquals(Money::parseByParser($parser, '1.00', new Currency('EUR')), Money::EUR(100));
    }
}
