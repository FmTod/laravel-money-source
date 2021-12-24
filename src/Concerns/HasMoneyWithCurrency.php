<?php

declare(strict_types=1);

namespace FmTod\Money\Concerns;

use FmTod\Money\Concerns\HasCurrency;
use FmTod\Money\Concerns\HasMoney;
use JetBrains\PhpStorm\Pure;

/**
 * Trait HasMoneyWithCurrency
 *
 * @author Serhii Andriichuk <andriichuk29@gmail.com>
 * @editor FmTod <it@fmtod.com>
 */
trait HasMoneyWithCurrency
{
    use HasMoney;
    use HasCurrency;

    #[Pure]
    public function getCurrencyColumnFor(string $field): string
    {
        return $this->getCurrencyColumn();
    }

    public function getCurrencyColumn(): string
    {
        return 'currency';
    }
}
