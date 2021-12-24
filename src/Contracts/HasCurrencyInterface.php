<?php

declare(strict_types=1);

namespace FmTod\Money\Contracts;

use \Money\Currency;

/**
 * Interface HasMoneyWithCurrencyInterface
 *
 * @author Serhii Andriichuk <andriichuk29@gmail.com>
 * @editor FmTod <it@fmtod.com>
 */
interface HasCurrencyInterface
{
    public function getDefaultCurrencyFor(string $field): Currency;
}
