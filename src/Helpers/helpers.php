<?php

if (! function_exists('currency')) {
    /**
     * currency.
     *
     * @param  string  $currency
     * @return \Money\Currency
     */
    function currency($currency)
    {
        return new \Money\Currency($currency);
    }
}

if (! function_exists('money')) {
    /**
     * money.
     *
     * @param  int|string  $amount
     * @param  string  $currency
     * @return \Fmtod\Money\Money
     */
    function money($amount, $currency = null)
    {
        return new Fmtod\Money\Money(
            $amount,
            new \Money\Currency($currency ?: Fmtod\Money\Money::getDefaultCurrency())
        );
    }
}

if (! function_exists('money_min')) {
    /**
     * money min.
     *
     * @param  \Fmtod\Money\Money  $first
     * @param  \Fmtod\Money\Money  ...$collection
     * @return \Fmtod\Money\Money
     */
    function money_min(Fmtod\Money\Money $first, Fmtod\Money\Money ...$collection)
    {
        return \Fmtod\Money\Money::min($first, ...$collection);
    }
}

if (! function_exists('money_max')) {
    /**
     * money max.
     *
     * @param  \Fmtod\Money\Money  $first
     * @param  \Fmtod\Money\Money  ...$collection
     * @return \Fmtod\Money\Money
     */
    function money_max(Fmtod\Money\Money $first, Fmtod\Money\Money ...$collection)
    {
        return \Fmtod\Money\Money::max($first, ...$collection);
    }
}

if (! function_exists('money_avg')) {
    /**
     * money avg.
     *
     * @param  \Fmtod\Money\Money  $first
     * @param  \Fmtod\Money\Money  ...$collection
     * @return \Fmtod\Money\Money
     */
    function money_avg(Fmtod\Money\Money $first, Fmtod\Money\Money ...$collection)
    {
        return \Fmtod\Money\Money::avg($first, ...$collection);
    }
}

if (! function_exists('money_sum')) {
    /**
     * money sum.
     *
     * @param  \Fmtod\Money\Money  $first
     * @param  \Fmtod\Money\Money  ...$collection
     * @return \Fmtod\Money\Money
     */
    function money_sum(Fmtod\Money\Money $first, Fmtod\Money\Money ...$collection)
    {
        return \Fmtod\Money\Money::sum($first, ...$collection);
    }
}

if (! function_exists('money_parse')) {
    /**
     * money parse.
     *
     * @param  mixed  $value
     * @param  \Money\Currency|string|null  $currency
     * @return \Fmtod\Money\Money|null
     */
    function money_parse($value, $currency = null)
    {
        return \Fmtod\Money\Money::parse($value, $currency);
    }
}

if (! function_exists('money_parse_by_bitcoin')) {
    /**
     * money parse by bitcoin.
     *
     * @param  string  $money
     * @param  string|null  $fallbackCurrency
     * @param  int  $fractionDigits
     * @return \Fmtod\Money\Money
     */
    function money_parse_by_bitcoin($money, $fallbackCurrency = null, $fractionDigits = 2)
    {
        return \Fmtod\Money\Money::parseByBitcoin($money, $fallbackCurrency, $fractionDigits);
    }
}

if (! function_exists('money_parse_by_decimal')) {
    /**
     * money parse by decimal.
     *
     * @param  string  $money
     * @param  string|null  $fallbackCurrency
     * @param  \Money\Currencies  $currencies
     * @return \FmTod\Money\Money
     */
    function money_parse_by_decimal($money, $fallbackCurrency = null, Money\Currencies $currencies = null)
    {
        return \Fmtod\Money\Money::parseByDecimal($money, $fallbackCurrency, $currencies);
    }
}

if (! function_exists('money_parse_by_intl')) {
    /**
     * money parse by intl.
     *
     * @param  string  $money
     * @param  string|null  $fallbackCurrency
     * @param  string|null  $locale
     * @param  \Money\Currencies  $currencies
     * @return \FmTod\Money\Money
     */
    function money_parse_by_intl($money, $fallbackCurrency = null, $locale = null, Money\Currencies $currencies = null)
    {
        return \Fmtod\Money\Money::parseByIntl($money, $fallbackCurrency, $locale, $currencies);
    }
}

if (! function_exists('money_parse_by_intl_localized_decimal')) {
    /**
     * money parse by intl localized decimal.
     *
     * @param  string  $money
     * @param  string  $fallbackCurrency
     * @param  string|null  $locale
     * @param  \Money\Currencies  $currencies
     * @return \FmTod\Money\Money
     */
    function money_parse_by_intl_localized_decimal(
        $money,
        $fallbackCurrency,
        $locale = null,
        \Money\Currencies $currencies = null
    ) {
        return \Fmtod\Money\Money::parseByIntlLocalizedDecimal($money, $fallbackCurrency, $locale, $currencies);
    }
}

if (!function_exists('format_money_as_currency')) {
    function format_money_as_currency(\Fmtod\Money\Money $money, string $locale = null): string
    {
       return $money->format($locale);
    }
}

if (!function_exists('format_money_as_decimal')) {
    function format_money_as_decimal(\Fmtod\Money\Money $money): string
    {
        return $money->formatByDecimal();
    }
}
