<?php

declare(strict_types=1);

namespace FmTod\Money\Concerns;

use JetBrains\PhpStorm\Pure;

/**
 * Trait HasMoneyWithCurrency
 *
 * @author Serhii Andriichuk <andriichuk29@gmail.com>
 *
 * @editor FmTod <it@fmtod.com>
 */
trait HasMoneyWithCurrency
{
    use HasMoney;
    use HasCurrency;

    #[Pure]
    public function hasCurrencyColumnFor(string $field): bool
    {
        return isset($this->attributes[$this->getCurrencyColumnFor($field)]);
    }

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
