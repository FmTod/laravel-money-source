<?php

use FmTod\Money\Money;

if (! function_exists('currency')) {
    /**
     * currency.
     *
     * @param  string  $currency
     * @return \Money\Currency
     */
    function currency($currency)
    {
        return new Money\Currency($currency);
    }
}

if (! function_exists('money')) {
    /**
     * money.
     *
     * @param  int|string  $amount
     * @param  string  $currency
     * @return Money
     */
    function money($amount, $currency = null)
    {
        return new FmTod\Money\Money(
            $amount,
            new Money\Currency($currency ?: FmTod\Money\Money::getDefaultCurrency())
        );
    }
}

if (! function_exists('money_min')) {
    /**
     * money min.
     *
     * @param  Money  $first
     * @param  Money  ...$collection
     * @return Money
     */
    function money_min(FmTod\Money\Money $first, FmTod\Money\Money ...$collection)
    {
        return FmTod\Money\Money::min($first, ...$collection);
    }
}

if (! function_exists('money_max')) {
    /**
     * money max.
     *
     * @param  Money  $first
     * @param  Money  ...$collection
     * @return Money
     */
    function money_max(FmTod\Money\Money $first, FmTod\Money\Money ...$collection)
    {
        return FmTod\Money\Money::max($first, ...$collection);
    }
}

if (! function_exists('money_avg')) {
    /**
     * money avg.
     *
     * @param  Money  $first
     * @param  Money  ...$collection
     * @return Money
     */
    function money_avg(FmTod\Money\Money $first, FmTod\Money\Money ...$collection)
    {
        return FmTod\Money\Money::avg($first, ...$collection);
    }
}

if (! function_exists('money_sum')) {
    /**
     * money sum.
     *
     * @param  Money  $first
     * @param  Money  ...$collection
     * @return Money
     */
    function money_sum(FmTod\Money\Money $first, FmTod\Money\Money ...$collection)
    {
        return FmTod\Money\Money::sum($first, ...$collection);
    }
}

if (! function_exists('money_parse')) {
    /**
     * money parse.
     *
     * @param  mixed  $value
     * @param  \Money\Currency|string|null  $currency
     * @return Money|null
     */
    function money_parse($value, $currency = null)
    {
        return FmTod\Money\Money::parse($value, $currency);
    }
}

if (! function_exists('money_parse_by_bitcoin')) {
    /**
     * money parse by bitcoin.
     *
     * @param  string  $money
     * @param  string|null  $forceCurrency
     * @param  int  $fractionDigits
     * @return Money
     */
    function money_parse_by_bitcoin($money, $forceCurrency = null, $fractionDigits = 2)
    {
        return FmTod\Money\Money::parseByBitcoin($money, $forceCurrency, $fractionDigits);
    }
}

if (! function_exists('money_parse_by_decimal')) {
    /**
     * money parse by decimal.
     *
     * @param  string  $money
     * @param  string|null  $forceCurrency
     * @param  \Money\Currencies  $currencies
     * @return Money
     */
    function money_parse_by_decimal($money, $forceCurrency = null, Money\Currencies $currencies = null)
    {
        return FmTod\Money\Money::parseByDecimal($money, $forceCurrency, $currencies);
    }
}

if (! function_exists('money_parse_by_intl')) {
    /**
     * money parse by intl.
     *
     * @param  string  $money
     * @param  string|null  $forceCurrency
     * @param  string|null  $locale
     * @param  \Money\Currencies  $currencies
     * @return Money
     */
    function money_parse_by_intl($money, $forceCurrency = null, $locale = null, Money\Currencies $currencies = null)
    {
        return FmTod\Money\Money::parseByIntl($money, $forceCurrency, $locale, $currencies);
    }
}

if (! function_exists('money_parse_by_intl_localized_decimal')) {
    /**
     * money parse by intl localized decimal.
     *
     * @param  string  $money
     * @param  string  $forceCurrency
     * @param  string|null  $locale
     * @param  \Money\Currencies  $currencies
     * @return Money
     */
    function money_parse_by_intl_localized_decimal(
        $money,
        $forceCurrency,
        $locale = null,
        Money\Currencies $currencies = null
    ) {
        return FmTod\Money\Money::parseByIntlLocalizedDecimal($money, $forceCurrency, $locale, $currencies);
    }
}

if (!function_exists('format_money_as_currency')) {
    function format_money_as_currency(Money $money, string $locale = null): string
    {
       return $money->format($locale);
    }
}

if (!function_exists('format_money_as_decimal')) {
    function format_money_as_decimal(Money $money): string
    {
        return $money->formatByDecimal();
    }
}
