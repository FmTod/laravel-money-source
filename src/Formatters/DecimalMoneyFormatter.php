<?php

namespace FmTod\Money\Formatters;

use Money\Currencies;
use Money\Money;
use Money\MoneyFormatter;

class DecimalMoneyFormatter implements MoneyFormatter
{
    private Currencies $currencies;

    public function __construct(Currencies $currencies = null)
    {
        $this->currencies = $currencies ?: \FmTod\Money\Money::getCurrencies();
    }

    public function format(Money $money): string
    {
        $valueBase = $money->getAmount();
        $negative = $valueBase[0] === '-';

        if ($negative) {
            $valueBase = substr($valueBase, 1);
        }

        $subunit = $this->currencies->subunitFor($money->getCurrency());
        $valueLength = strlen($valueBase);

        if ($valueLength > $subunit) {
            $formatted = substr($valueBase, 0, $valueLength - $subunit);
            $decimalDigits = substr($valueBase, $valueLength - $subunit);

            if (strlen($decimalDigits) > 0) {
                $formatted .= '.'.$decimalDigits;
            }
        } else {
            $formatted = '0.'.str_pad('', $subunit - $valueLength, '0').$valueBase;
        }

        if ($negative) {
            return '-'.$formatted;
        }

        assert($formatted !== '');

        return $formatted;
    }
}
