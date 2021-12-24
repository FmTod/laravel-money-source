<?php

namespace FmTod\Money\Serializers;

use FmTod\Money\Contracts\MoneySerializer;
use FmTod\Money\Money;

class FormatSerializer implements MoneySerializer
{
    public function __invoke(Money $money): string
    {
        return $money->format();
    }
}
