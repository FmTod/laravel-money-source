<?php

namespace FmTod\Money\Rules;

use Closure;
use FmTod\Money\Money;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use InvalidArgumentException;

class CompareMoney implements DataAwareRule, ValidationRule
{
    /**
     * All the data under validation.
     */
    protected array $data = [];

    /**
     * Currency code to use for parsing money values.
     */
    protected string $currency;

    /**
     * Create a new rule instance.
     *
     * @param  \FmTod\Money\Money|mixed  $expected
     * @param  null  $callback
     */
    public function __construct(
        protected string $expected,
        protected string $operator = '=',
        ?string $currency = null,
        protected $callback = null,
    ) {
        $this->currency = $currency ?? config('money.currency');
    }

    /**
     * Set the data under validation.
     *
     * @param  array  $data
     * @return $this
     */
    public function setData($data): static
    {
        $this->data = $data;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $actual = Money::parse($value, $this->currency);

        $expected = Money::parse(data_get($this->data, $this->expected) ?? 0, $this->currency);

        if (is_callable($this->callback)) {
            $expected = value($this->callback, $expected);
        }

        $operator = match ($this->operator) {
            '>=', 'greaterThanOrEqual' => 'greaterThanOrEqual',
            '>', 'gt', 'greaterThan' => 'greaterThan',

            '<=', 'lessThanOrEqual' => 'lessThanOrEqual',
            '<', 'lt', 'lessThan' => 'lessThan',

            '=', 'eq', 'equal', 'equals' => 'equals',

            default => throw new InvalidArgumentException("Illegal operator provided $this->operator.")
        };

        if (! $actual->$operator($expected)) {
            $fail($this->message());
        }
    }

    protected function message(): string
    {
        $operator = match ($this->operator) {
            '>=', 'greaterThanOrEqual' => 'greater than or equal to',
            '>', 'gt', 'greaterThan' => 'greater than',

            '<=', 'lessThanOrEqual' => 'less than or equal to',
            '<', 'lt', 'lessThan' => 'less than',

            '=', 'eq', 'equal', 'equals' => 'equal to',

            default => throw new InvalidArgumentException('Illegal operator provided.')
        };

        return sprintf(':attribute must be %s %s', $operator, $this->expected);
    }

    /**
     * Convert the rule to a validation string.
     */
    public function __toString(): string
    {
        return 'compare_money';
    }
}
