<?php

declare(strict_types=1);

namespace FmTod\Money\Concerns;

use FmTod\Money\Casts\CurrencyCast;
use FmTod\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Money\Currency;

/**
 * Trait HasCurrency
 *
 * @author Serhii Andriichuk <andriichuk29@gmail.com>
 *
 * @editor FmTod <it@fmtod.com>
 */
trait HasCurrency
{
    private array $currencyFields = [];

    /**
     * Default the currency columns on create, registered once for the class.
     *
     * Registering this from the instance initializer instead — as this trait used to — adds a
     * listener per model constructed, and the closure closes over the instance that registered
     * it. The dispatcher then holds every model ever hydrated for the life of the process: a
     * command that walks a few hundred thousand rows keeps every one of them, and each create
     * fires one listener per instance built so far.
     */
    public static function bootHasCurrency(): void
    {
        static::creating(static function (Model $model): void {
            foreach ($model->currencyFields as $currencyField) {
                if ($model->{$currencyField} === null) {
                    $model->{$currencyField} = $model->getDefaultCurrencyFor($currencyField);
                }
            }
        });
    }

    protected function initializeHasCurrency(): void
    {
        $this->grabCurrencyFields();
    }

    private function grabCurrencyFields(): void
    {
        /**
         * @var string $field
         * @var string $cast
         */
        foreach ($this->casts as $field => $cast) {
            if ($cast !== CurrencyCast::class) {
                continue;
            }

            $this->currencyFields[] = $field;
        }
    }

    public function getDefaultCurrencyFor(string $field): Currency
    {
        return $this->getDefaultCurrency();
    }

    private function getDefaultCurrency(): Currency
    {
        return new Currency(Money::getDefaultCurrency());
    }
}
