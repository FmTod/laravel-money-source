<?php

namespace FmTod\Money\Traits;

use FmTod\Money\Money;
use InvalidArgumentException;
use Money\Currencies;
use Money\Currency;
use Money\Exception\ParserException;
use Money\MoneyParser;
use Money\Parser\AggregateMoneyParser;
use Money\Parser\BitcoinMoneyParser;
use Money\Parser\DecimalMoneyParser;
use Money\Parser\IntlLocalizedDecimalParser;
use Money\Parser\IntlMoneyParser;
use NumberFormatter;

trait MoneyParserTrait
{
    /**
     * Convert the given value into an instance of Money.
     */
    public static function parse(mixed $value, Currency|string|null $currency = null, int $bitCoinDigits = 2): Money
    {
        if ($value instanceof Money) {
            return $value;
        }

        if ($value instanceof \Money\Money) {
            return static::fromMoney($value);
        }

        if (is_string($currency)) {
            $currency = new Currency($currency);
        }

        if (is_scalar($value)) {
            $locale = static::getLocale();
            $currencies = static::getCurrencies();

            try {
                return static::parseByAggregate($value, null, [
                    new IntlMoneyParser(new NumberFormatter($locale, NumberFormatter::CURRENCY), $currencies),
                    new IntlLocalizedDecimalParser(new NumberFormatter($locale, NumberFormatter::DECIMAL), $currencies),
                    new DecimalMoneyParser($currencies),
                    new BitcoinMoneyParser($bitCoinDigits),
                ]);
            } catch (ParserException $e) {
                return static::parseByAggregate($value, $currency, [
                    new IntlMoneyParser(new NumberFormatter($locale, NumberFormatter::CURRENCY), $currencies),
                    new IntlLocalizedDecimalParser(new NumberFormatter($locale, NumberFormatter::DECIMAL), $currencies),
                    new DecimalMoneyParser($currencies),
                    new BitcoinMoneyParser($bitCoinDigits),
                ]);
            }
        }

        throw new InvalidArgumentException(sprintf('Invalid value %s', json_encode($value)));
    }

    /**
     * Parse by aggregate.
     *
     * @param  MoneyParser[]  $parsers
     */
    public static function parseByAggregate(string $money, Currency|string|null $fallbackCurrency = null, array $parsers = []): Money
    {
        $parser = new AggregateMoneyParser($parsers);

        return static::parseByParser($parser, $money, $fallbackCurrency);
    }

    /**
     * Parse by bitcoin.
     */
    public static function parseByBitcoin(string $money, Currency|string|null $fallbackCurrency = null, int $fractionDigits = 2): Money
    {
        $parser = new BitcoinMoneyParser($fractionDigits);

        return static::parseByParser($parser, $money, $fallbackCurrency);
    }

    /**
     * Parse by decimal.
     */
    public static function parseByDecimal(string $money, Currency|string|null $fallbackCurrency = null, ?Currencies $currencies = null): Money
    {
        $parser = new DecimalMoneyParser($currencies ?: static::getCurrencies());

        return static::parseByParser($parser, $money, $fallbackCurrency);
    }

    /**
     * Parse by intl.
     */
    public static function parseByIntl(
        string $money,
        Currency|string|null $fallbackCurrency = null,
        ?string $locale = null,
        ?Currencies $currencies = null,
        int $style = NumberFormatter::CURRENCY
    ): Money {
        $numberFormatter = new NumberFormatter($locale ?: static::getLocale(), $style);
        $parser = new IntlMoneyParser($numberFormatter, $currencies ?: static::getCurrencies());

        return static::parseByParser($parser, $money, $fallbackCurrency);
    }

    /**
     * Parse by intl localized decimal.
     */
    public static function parseByIntlLocalizedDecimal(
        string $money,
        Currency|string|null $fallbackCurrency = null,
        ?string $locale = null,
        ?Currencies $currencies = null,
        int $style = NumberFormatter::DECIMAL
    ): Money {
        $numberFormatter = new NumberFormatter($locale ?: static::getLocale(), $style);
        $parser = new IntlLocalizedDecimalParser($numberFormatter, $currencies ?: static::getCurrencies());

        return static::parseByParser($parser, $money, $fallbackCurrency);
    }

    /**
     * Parse by parser.
     */
    public static function parseByParser(MoneyParser $parser, string $money, Currency|string|null $fallbackCurrency = null): Money
    {
        if (is_string($fallbackCurrency)) {
            $fallbackCurrency = new Currency($fallbackCurrency);
        }

        return static::convert($parser->parse($money, $fallbackCurrency));
    }
}
