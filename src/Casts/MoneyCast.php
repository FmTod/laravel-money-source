<?php

namespace FmTod\Money\Casts;

use FmTod\Money\Model\HasCurrencyInterface;
use FmTod\Money\Model\HasMoneyWithCurrencyInterface;
use FmTod\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Money\Currency;

class MoneyCast implements CastsAttributes
{
    /**
     * Transform the attribute from the underlying model values.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string                              $key
     * @param mixed                               $value
     * @param array                               $attributes
     *
     * @return \FmTod\Money\Money|null
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === null) {
            return null;
        }

        return Money::parseByDecimal($value, $this->resolveCurrencyColumn($model, $key, $attributes));
    }

    /**
     * Transform the attribute to its underlying model values.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string                              $key
     * @param mixed                               $value
     * @param array                               $attributes
     *
     * @return string|array
     *@throws \InvalidArgumentException
     *
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if ($value === null) {
            return $value;
        }

        $money = $value instanceof Money
            ? $value
            : Money::parseByDecimal($value, $this->resolveCurrencyColumn($model, $key, $attributes));

        $decimalAmount = $money->formatByDecimal();
        if ($this->hasCurrencyColumn($model, $key)) {
            return [
                $key => $decimalAmount,
                $model->getCurrencyColumnFor($key) => $money->getCurrency()->getCode(),
            ];
        }

        return $decimalAmount;
    }

    private function resolveCurrencyColumn($model, string $key, $attributes): ?Currency
    {
        if (!$this->hasCurrencyColumn($model, $key)) {
            return null;
        }

        $default = $model instanceof HasCurrencyInterface
            ? $model->getDefaultCurrencyFor($key)->getCode()
            : null;

        return new Currency($attributes[$model->getCurrencyColumnFor($key)] ?? $default);
    }

    private function hasCurrencyColumn($model, $key): bool
    {
        return $model instanceof HasMoneyWithCurrencyInterface && $model->getCurrencyColumnFor($key);
    }
}
