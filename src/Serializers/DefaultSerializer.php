<?php

namespace FmTod\Money\Serializers;

use FmTod\Money\Contracts\MoneySerializer;
use FmTod\Money\Money;

class DefaultSerializer implements MoneySerializer
{
    public function __invoke(Money $money): array
    {
        return array_merge(
            $money->getMoney()->jsonSerialize(),
            ['formatted' => $money->render()]
        );
    }
}
