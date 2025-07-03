<?php

declare(strict_types=1);

namespace FmTod\Money\Concerns;

/**
 * Trait HasMoneyWithCurrency
 *
 * @author Serhii Andriichuk <andriichuk29@gmail.com>
 *
 * @editor FmTod <it@fmtod.com>
 */
trait HasMoneyWithCurrency
{
    use HasCurrency;
    use HasMoney;

    public function hasCurrencyColumnFor(string $field): bool
    {
        return isset($this->attributes[$this->getCurrencyColumnFor($field)]);
    }

    public function getCurrencyColumnFor(string $field): string
    {
        return $this->getCurrencyColumn();
    }

    public function getCurrencyColumn(): string
    {
        return 'currency';
    }
}
