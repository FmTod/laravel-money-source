<?php

namespace FmTod\Money\Contracts;

use FmTod\Money\Money;

interface MoneySerializer
{
    public function __invoke(Money $money): array;
}
