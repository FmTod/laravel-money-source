<?php

declare(strict_types=1);

namespace FmTod\Money\Contracts;

/**
 * Interface HasMoneyWithCurrencyInterface
 *
 * @author Serhii Andriichuk <andriichuk29@gmail.com>
 * @editor FmTod <it@fmtod.com>
 */
interface HasMoneyWithCurrencyInterface
{
    public function getCurrencyColumnFor(string $field): string;
}
