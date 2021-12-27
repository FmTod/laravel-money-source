<?php

namespace FmTod\Money\Casts;

use FmTod\Money\Contracts\HasCurrencyInterface;
use FmTod\Money\Contracts\HasMoneyWithCurrencyInterface;
use FmTod\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;
use Money\Currency;

class MoneyCast implements CastsAttributes
{
    public function __construct(
        protected bool    $updateCurrencyColumn = true,
        protected ?string $forceCurrency = null,
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

        return Money::parse($value, $this->resolveCurrencyColumn($model, $key, $attributes));
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

        try {
            $currency = $this->resolveCurrencyColumn($model, $key, $attributes);
            $money = Money::parse($value, $currency);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException(
                sprintf('Invalid data provided for %s::$%s', get_class($model), $key)
            );
        }

        if ($this->updateCurrencyColumn && $model instanceof HasMoneyWithCurrencyInterface && $model->hasCurrencyColumnFor($key)) {
            return [
                $key => $money->format(),
                $model->getCurrencyColumnFor($key) => $money->getCurrency()->getCode(),
            ];
        }

        return $money->format();
    }

    /**
     * Resolve currency from the currency column in the model or the default currency.
     *
     * @param $model
     * @param string $key
     * @param $attributes
     * @return \Money\Currency
     */
    private function resolveCurrencyColumn($model, string $key, $attributes): Currency
    {
        if (! is_null($this->forceCurrency)) {
            return new Currency($this->forceCurrency);
        }

        if ($model instanceof HasMoneyWithCurrencyInterface) {
            if ($model->hasCurrencyColumnFor($key) && isset($attributes[$model->getCurrencyColumnFor($key)])) {
                return new Currency($attributes[$model->getCurrencyColumnFor($key)]);
            }

            return $model->getDefaultCurrencyFor($key);
        }

        return new Currency(Money::getDefaultCurrency());
    }
}
