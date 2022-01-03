<?php

namespace FmTod\Money\Serializers;

use FmTod\Money\Contracts\MoneySerializer;
use FmTod\Money\Money;
use JetBrains\PhpStorm\ArrayShape;

class DecimalSerializer implements MoneySerializer
{
    #[ArrayShape(['value' => "string", 'currency' => "string"])]
    public function __invoke(Money $money): array
    {
        return [
            'value' => $money->formatByDecimal(),
            'currency' => $money->getCurrency()->jsonSerialize(),
        ];
    }
}
