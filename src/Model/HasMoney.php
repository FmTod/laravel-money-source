<?php

declare(strict_types=1);

namespace FmTod\Money\Model;

use FmTod\Money\Casts\MoneyCast;

/**
 * Trait HasMoney
 *
 * @author Serhii Andriichuk <andriichuk29@gmail.com>
 * @editor FmTod <it@fmtod.com>
 */
trait HasMoney
{
    /**
     * @var array
     */
    private $moneyCasts = [];

    protected function initializeHasMoney(): void
    {
        $this->initializeAccessors();
    }

    protected function initializeAccessors(): void
    {
        foreach ($this->casts as $field => $cast) {
            if ($cast !== MoneyCast::class || $this->hasAccessor($field)) {
                continue;
            }

            $this->addAccessor($field);
        }
    }

    protected function addAccessor(string $field): void
    {
        $this->moneyCasts[$field . '_as_currency'] = function () use ($field) {
            return format_money_as_currency($this->{$field});
        };

        $this->moneyCasts[$field . '_as_decimal'] = function () use ($field) {
            return format_money_as_decimal($this->{$field});
        };
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (!$this->hasAccessor($key)) {
            return parent::__get($key);
        }

        return $this->moneyCasts[$key]();
    }

    protected function hasAccessor(string $field): bool
    {
        return isset($this->moneyCasts[$field]);
    }
}
