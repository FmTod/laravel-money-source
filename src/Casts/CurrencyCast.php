<?php

declare(strict_types=1);

namespace FmTod\Money\Casts;

use FmTod\Money\Contracts\HasCurrencyInterface;
use FmTod\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Money\Currency;

/**
 * Class CurrencyCast
 *
 * @author Serhii Andriichuk <andriichuk29@gmail.com>
 *
 * @editor FmTod <it@fmtod.com>
 */
final class CurrencyCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Contracts\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  string  $value
     * @param  array  $attributes
     *
     * @throws \Money\Exception\UnknownCurrencyException
     */
    public function get($model, $key, $value, $attributes): Currency
    {
        return $this->resolveCurrency($model, $key, $value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  Model  $model
     * @param  string  $key
     * @param  \Money\Currency|string  $value
     * @param  array  $attributes
     *
     * @throws \Money\Exception\UnknownCurrencyException
     */
    public function set($model, $key, $value, $attributes): string
    {
        return $this->resolveCurrency($model, $key, $value)->getCode();
    }

    /**
     * Resolve currency from the currency column in the model or the default currency.
     *
     * @param  \Money\Currency|string  $value
     *
     * @throws \Money\Exception\UnknownCurrencyException
     */
    private function resolveCurrency(Model $model, string $key, $value): Currency
    {
        $default = $model instanceof HasCurrencyInterface
            ? $model->getDefaultCurrencyFor($key)->getCode()
            : Money::getDefaultCurrency();

        if ($value instanceof Currency) {
            return $value;
        }

        return new Currency($value ?? $default);
    }
}
