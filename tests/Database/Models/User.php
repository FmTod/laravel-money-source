<?php

namespace FmTod\Money\Tests\Database\Models;

use FmTod\Money\Casts\CurrencyCast;
use FmTod\Money\Casts\MoneyCast;
use FmTod\Money\Concerns\HasMoneyWithCurrency;
use FmTod\Money\Contracts\HasMoneyWithCurrencyInterface;
use Illuminate\Database\Eloquent\Model;
use Money\Currency;

/**
 * The testing user model.
 */
class User extends Model implements HasMoneyWithCurrencyInterface
{
    use HasMoneyWithCurrency;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'money',
        'wage',
        'debits',
        'currency',
    ];

    /**
     * The attributes to cast.
     *
     * @var array
     */
    protected $casts = [
        'money' => MoneyCast::class,
        'wage' => MoneyCast::class,
        'debits' => MoneyCast::class.':0,USD',
        'currency' => CurrencyCast::class,
    ];

    public function hasCurrencyColumnFor(string $field): bool
    {
        return match ($field) {
            'wage' => false,
            default => true,
        };
    }

    public function getDefaultCurrencyFor(string $field): Currency
    {
        return match ($field) {
            'wage' => new Currency('EUR'),
            default => $this->getDefaultCurrency(),
        };
    }
}
