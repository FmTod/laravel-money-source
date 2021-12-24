<?php

namespace FmTod\Money\Casts;

use FmTod\Money\Contracts\HasCurrencyInterface;
use FmTod\Money\Contracts\HasMoneyWithCurrencyInterface;
use FmTod\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Money\Currency;

class MoneyCast implements CastsAttributes
{
    public function __construct(
        protected bool $updateCurrencyColumn = true
    ) {}

    /**
     *
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return \FmTod\Money\Money|null
     */
    public function get($model, string $key, $value, array $attributes): ?Money
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
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return array|string|null
     */
    public function set($model, string $key, $value, array $attributes): array|string|null
    {
        if ($value === null) {
            return null;
        }

        $money = $value instanceof Money
            ? $value
            : Money::parseByDecimal($value, $this->resolveCurrencyColumn($model, $key, $attributes));

        $decimalAmount = $money->formatByDecimal();

        if ($this->updateCurrencyColumn && $this->hasCurrencyColumn($model, $key)) {
            return [
                $key => $decimalAmount,
                $model->getCurrencyColumnFor($key) => $money->getCurrency()->getCode(),
            ];
        }

        return $decimalAmount;
    }

    /**
     * Resolve currency from the currency column in the model or the default currency.
     *
     * @param $model
     * @param string $key
     * @param $attributes
     * @return \Money\Currency|null
     */
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

    /**
     * Determine if the provided model has a currency column for the provided attribute.
     *
     * @param $model
     * @param $key
     * @return bool
     */
    private function hasCurrencyColumn($model, $key): bool
    {
        return $model instanceof HasMoneyWithCurrencyInterface && $model->getCurrencyColumnFor($key);
    }
}
