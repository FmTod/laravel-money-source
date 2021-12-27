<?php

namespace FmTod\Money\Traits;

use InvalidArgumentException;
use Money\Currencies;
use Money\Currencies\BitcoinCurrencies;
use Money\Formatter\AggregateMoneyFormatter;
use Money\Formatter\BitcoinMoneyFormatter;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Formatter\IntlLocalizedDecimalFormatter;
use Money\Formatter\IntlMoneyFormatter;
use Money\MoneyFormatter;
use NumberFormatter;

trait MoneyFormatterTrait
{
    /**
     * Format.
     *
     * @param string|null $locale
     * @param \Money\Currencies|null $currencies
     * @param int $style
     * @return string
     */
    public function format(string $locale = null, Currencies $currencies = null, int $style = NumberFormatter::CURRENCY): string
    {
        $defaultFormatter = config('money.formatter');

        if (is_null($defaultFormatter)) {
            return $this->formatByIntl($locale, $currencies, $style);
        }

        $formatter = null;

        if (is_string($defaultFormatter)) {
            $formatter = app($defaultFormatter);
        }

        if (is_array($defaultFormatter) && count($defaultFormatter) === 2) {
            $formatter = app($defaultFormatter[0], $defaultFormatter[1]);
        }

        if ($formatter instanceof MoneyFormatter) {
            return $this->formatByFormatter($formatter);
        }

        throw new InvalidArgumentException(sprintf('Invalid default formatter %s', $defaultFormatter));
    }

    /**
     * Format by aggregate.
     *
     * @param  MoneyFormatter[]  $formatters
     * @return string
     */
    public function formatByAggregate(array $formatters): string
    {
        $formatter = new AggregateMoneyFormatter($formatters);

        return $this->formatByFormatter($formatter);
    }

    /**
     * Format by bitcoin.
     *
     * @param int $fractionDigits
     * @param  \Money\Currencies  $currencies
     * @return string
     */
    public function formatByBitcoin(int $fractionDigits = 2, Currencies $currencies = null): string
    {
        $formatter = new BitcoinMoneyFormatter($fractionDigits, $currencies ?: new BitcoinCurrencies());

        return $this->formatByFormatter($formatter);
    }

    /**
     * Format by decimal.
     *
     * @param  \Money\Currencies  $currencies
     * @return string
     */
    public function formatByDecimal(Currencies $currencies = null): string
    {
        $formatter = new DecimalMoneyFormatter($currencies ?: static::getCurrencies());

        return $this->formatByFormatter($formatter);
    }

    /**
     * Format by intl.
     *
     * @param string|null $locale
     * @param  \Money\Currencies  $currencies
     * @param int $style
     * @return string
     */
    public function formatByIntl(string $locale = null, Currencies $currencies = null, int $style = NumberFormatter::CURRENCY): string
    {
        $numberFormatter = new NumberFormatter($locale ?: static::getLocale(), $style);
        $formatter = new IntlMoneyFormatter($numberFormatter, $currencies ?: static::getCurrencies());

        return $this->formatByFormatter($formatter);
    }

    /**
     * Format by intl localized decimal.
     *
     * @param string|null $locale
     * @param  \Money\Currencies  $currencies
     * @param int $style
     * @return string
     */
    public function formatByIntlLocalizedDecimal(
        string     $locale = null,
        Currencies $currencies = null,
        int        $style = NumberFormatter::CURRENCY
    ): string
    {
        $numberFormatter = new NumberFormatter($locale ?: static::getLocale(), $style);
        $formatter = new IntlLocalizedDecimalFormatter($numberFormatter, $currencies ?: static::getCurrencies());

        return $this->formatByFormatter($formatter);
    }

    /**
     * Format by formatter.
     *
     * @param  \Money\MoneyFormatter  $formatter
     * @return string
     */
    public function formatByFormatter(MoneyFormatter $formatter): string
    {
        return $formatter->format($this->money);
    }
}
