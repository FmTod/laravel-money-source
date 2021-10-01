<?php

namespace FmTod\Money;

use FmTod\Money\Concerns\MoneyFactory;
use FmTod\Money\Traits\CurrenciesTrait;
use FmTod\Money\Traits\LocaleTrait;
use FmTod\Money\Traits\MoneyFormatterTrait;
use FmTod\Money\Traits\MoneyParserTrait;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Traits\Macroable;
use JsonSerializable;
use Money\Currency;

/**
 * @mixin \Money\Money
 */
class Money implements Arrayable, Jsonable, JsonSerializable, Renderable
{
    use CurrenciesTrait;
    use LocaleTrait;
    use MoneyFactory {
        MoneyFactory::__callStatic as factoryCallStatic;
    }
    use MoneyFormatterTrait;
    use MoneyParserTrait;
    use Macroable {
        Macroable::__call as macroCall;
    }

    /**
     * @var \Money\Money
     */
    protected $money;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Money.
     *
     * @param int|string $amount
     * @param \Money\Currency|string $currency
     * @return void
     * @throws \Money\Exception\UnknownCurrencyException
     */
    public function __construct($amount, $currency)
    {
        if (!$currency instanceof Currency) {
            $currency = new Currency($currency);
        }

        $this->money = new \Money\Money($amount, $currency);
    }

    /**
     * __call.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return \FmTod\Money\Money|\FmTod\Money\Money[]|mixed
     */
    public function __call($method, array $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        if (! method_exists($this->money, $method)) {
            return $this;
        }

        $result = call_user_func_array([$this->money, $method], static::getArguments($parameters));

        $methods = [
            'add', 'subtract',
            'multiply', 'divide', 'mod',
            'absolute', 'negative',
            'allocate', 'allocateTo',
        ];

        if (! in_array($method, $methods)) {
            return $result;
        }

        return static::convertResult($result);
    }

    /**
     * __toString.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * __callStatic.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return \FmTod\Money\Money
     */
    public static function __callStatic($method, array $parameters)
    {
        if (in_array($method, ['min', 'max', 'avg', 'sum'])) {
            $result = call_user_func_array([\Money\Money::class, $method], static::getArguments($parameters));

            return static::convert($result);
        }

        return static::factoryCallStatic($method, $parameters);
    }

    /**
     * Convert.
     *
     * @param  \Money\Money  $instance
     * @return \FmTod\Money\Money
     */
    public static function convert(\Money\Money $instance): Money
    {
        return static::fromMoney($instance);
    }

    /**
     * Get money.
     *
     * @return \Money\Money
     */
    public function getMoney(): \Money\Money
    {
        return $this->money;
    }

    /**
     * Attributes.
     *
     * @param  array  $attributes
     */
    public function attributes(array $attributes = []): void
    {
        $this->attributes = $attributes;
    }

    /**
     * Json serialize.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array_merge(
            $this->attributes,
            $this->money->jsonSerialize(),
            ['formatted' => $this->render()]
        );
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->jsonSerialize();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render(): string
    {
        return $this->format();
    }

    /**
     * Get arguments.
     *
     * @param  array  $arguments
     * @return array
     */
    private static function getArguments(array $arguments = []): array
    {
        $args = [];

        foreach ($arguments as $argument) {
            $args[] = $argument instanceof static ? $argument->getMoney() : $argument;
        }

        return $args;
    }

    /**
     * Convert result.
     *
     * @param  mixed  $result
     * @return \FmTod\Money\Money|\FmTod\Money\Money[]
     */
    private static function convertResult($result)
    {
        if (! is_array($result)) {
            return static::convert($result);
        }

        $results = [];

        foreach ($result as $item) {
            $results[] = static::convert($item);
        }

        return $results;
    }
}
