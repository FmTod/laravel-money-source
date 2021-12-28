<?php

namespace FmTod\Money\Formatters;

use FmTod\Money\Money;
use Money\Currencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\MoneyFormatter;
use NumberFormatter;

class CurrencySymbolMoneyFormatter implements MoneyFormatter
{
    protected bool $right;

    protected string $locale;

    protected Currencies $currencies;

    /**
     * Instantiate the class.
     *
     * @param bool $right
     * @param string|null $locale
     * @param  \Money\Currencies|null  $currencies
     */
    public function __construct(bool $right = false, string $locale = null, Currencies $currencies = null)
    {
        $this->right = $right;
        $this->locale = $locale ?: Money::getLocale();
        $this->currencies = $currencies ?: Money::getCurrencies();
    }

    /**
     * Formats a Money object as string.
     *
     * @param \Money\Money $money
     * @return string
     */
    public function format(\Money\Money $money): string
    {
        $numberFormatter = new NumberFormatter( $this->locale."@currency=".$money->getCurrency()->getCode(), NumberFormatter::CURRENCY);
        $symbol = $numberFormatter->getSymbol(NumberFormatter::CURRENCY_SYMBOL);

        $formatter = new DecimalMoneyFormatter($this->currencies);
        $value = $formatter->format($money);

        return $this->right ? $value.$symbol : $symbol.$value;
    }
}
