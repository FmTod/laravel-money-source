<?php

namespace FmTod\Money\Casts;

use FmTod\Money\Contracts\HasMoneyWithCurrencyInterface;
use FmTod\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Money\Currency;

class MoneyCast implements CastsAttributes
{
    public function __construct(
        protected bool $updateCurrencyColumn = true,
        protected ?string $forceCurrency = null,
    ) {}

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  mixed  $value
     */
    public function get($model, string $key, $value, array $attributes): ?Money
    {
        if ($value === null) {
            return null;
        }

        $this->parse($model, $key, $value, $attributes);
    }

    /**
     * Transform the attribute to its underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  mixed  $value
     */
    public function set($model, string $key, $value, array $attributes): array|string|null
    {
        if ($value === null) {
            return null;
        }

        try {
            $this->parse($model, $key, $value, $attributes);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException(
                sprintf('Invalid data provided for %s::$%s', get_class($model), $key),
            );
        }

        if ($this->updateCurrencyColumn && $model instanceof HasMoneyWithCurrencyInterface && $model->hasCurrencyColumnFor($key)) {
            return [
                $key => $this->format($money),
                $model->getCurrencyColumnFor($key) => $money->getCurrency()->getCode(),
            ];
        }

        return $this->format($money);
    }

    /**
     * Resolve currency from the currency column in the model or the default currency.
     */
    protected function resolveCurrencyColumn($model, string $key, $attributes): Currency
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

    /**
     * Get formatted money object.
     */
    protected function format(Money $money): mixed
    {
        return $money->{match (config('money.cast')) {
            'bitcoin' => 'formatByBitcoin',
            'intl' => 'formatByIntl',
            default => 'formatByDecimal',
        }}();
    }

    /**
     * Parse the money value. Retry parsing the value without spaces if the parser fails.
     */
    protected function parse(Model $model, string $key, mixed $value, array $attributes)
    {
        try {
            return Money::parse($value, $this->resolveCurrencyColumn($model, $key, $attributes));
        } catch (ParserException $e) {
            return Money::parse(preg_replace('/\s/', '', $value), $this->resolveCurrencyColumn($model, $key, $attributes));
        }
    }
}
