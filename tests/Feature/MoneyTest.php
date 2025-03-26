<?php

namespace FmTod\Money\Tests\Feature;

use FmTod\Money\Money;
use FmTod\Money\Tests\TestCase;
use Money\Currency;

class MoneyTest extends TestCase
{
    public function test_convert()
    {
        static::assertEquals(Money::USD(25), Money::convert(new \Money\Money(25, new Currency('USD'))));
        static::assertEquals(Money::EUR(25), Money::convert(new \Money\Money(25, new Currency('EUR'))));
    }

    public function test_min()
    {
        static::assertEquals(Money::USD(10), Money::min(Money::USD(10), Money::USD(20), Money::USD(30)));
        static::assertEquals(Money::EUR(10), Money::min(Money::EUR(10), Money::EUR(20), Money::EUR(30)));
    }

    public function test_max()
    {
        static::assertEquals(Money::USD(30), Money::max(Money::USD(10), Money::USD(20), Money::USD(30)));
        static::assertEquals(Money::EUR(30), Money::max(Money::EUR(10), Money::EUR(20), Money::EUR(30)));
    }

    public function test_avg()
    {
        static::assertEquals(Money::USD(20), Money::avg(Money::USD(10), Money::USD(20), Money::USD(30)));
        static::assertEquals(Money::EUR(20), Money::avg(Money::EUR(10), Money::EUR(20), Money::EUR(30)));
    }

    public function test_sum()
    {
        static::assertEquals(Money::USD(60), Money::sum(Money::USD(10), Money::USD(20), Money::USD(30)));
        static::assertEquals(Money::EUR(60), Money::sum(Money::EUR(10), Money::EUR(20), Money::EUR(30)));
    }

    public function test_add()
    {
        static::assertEquals(Money::USD(25), Money::USD(10)->add(Money::USD(15)));
        static::assertEquals(Money::USD(40), Money::USD(10)->add(Money::USD(15), Money::USD(15)));
        static::assertEquals(Money::EUR(25), Money::EUR(10)->add(Money::EUR(15)));
        static::assertEquals(Money::EUR(40), Money::EUR(10)->add(Money::EUR(15), Money::EUR(15)));
    }

    public function test_subtract()
    {
        static::assertEquals(Money::USD(20), Money::USD(25)->subtract(Money::USD(5)));
        static::assertEquals(Money::USD(15), Money::USD(25)->subtract(Money::USD(5), Money::USD(5)));
        static::assertEquals(Money::EUR(15), Money::EUR(20)->subtract(Money::EUR(5)));
        static::assertEquals(Money::EUR(10), Money::EUR(20)->subtract(Money::EUR(5), Money::EUR(5)));
    }

    public function test_multiply()
    {
        static::assertEquals(Money::USD(10), Money::USD(5)->multiply(2));
        static::assertEquals(Money::EUR(10), Money::EUR(5)->multiply(2));
    }

    public function test_divide()
    {
        static::assertEquals(Money::USD(10), Money::USD(20)->divide(2));
        static::assertEquals(Money::EUR(10), Money::EUR(20)->divide(2));
    }

    public function test_mod()
    {
        static::assertEquals(Money::USD(115), Money::USD(415)->mod(Money::USD(150)));
        static::assertEquals(Money::EUR(230), Money::EUR(830)->mod(Money::EUR(300)));
    }

    public function test_absolute()
    {
        static::assertEquals(Money::USD(10), Money::USD(-10)->absolute());
        static::assertEquals(Money::EUR(10), Money::EUR(-10)->absolute());
    }

    public function test_negative()
    {
        static::assertEquals(Money::USD(-10), Money::USD(10)->negative());
        static::assertEquals(Money::EUR(-10), Money::EUR(10)->negative());
    }

    public function test_ratio_of()
    {
        static::assertEquals('20', (float) Money::USD(60)->ratioOf(Money::USD(3)));
        static::assertEquals('15', (float) Money::EUR(30)->ratioOf(Money::EUR(2)));
    }

    public function test_same_currency()
    {
        static::assertTrue(Money::USD(100)->isSameCurrency(Money::USD(200)));
        static::assertFalse(Money::USD(100)->isSameCurrency(Money::EUR(200)));
    }

    public function test_equality()
    {
        static::assertTrue(Money::USD(100)->equals(Money::USD(100)));
        static::assertFalse(Money::EUR(100)->equals(Money::EUR(200)));
    }

    public function test_greater_than()
    {
        static::assertTrue(Money::USD(100)->greaterThan(Money::USD(50)));
        static::assertFalse(Money::EUR(100)->greaterThan(Money::EUR(100)));
    }

    public function test_greater_than_or_equal()
    {
        static::assertTrue(Money::USD(100)->greaterThanOrEqual(Money::USD(100)));
        static::assertTrue(Money::USD(100)->greaterThanOrEqual(Money::USD(50)));
        static::assertFalse(Money::EUR(100)->greaterThanOrEqual(Money::EUR(150)));
    }

    public function test_less_than()
    {
        static::assertTrue(Money::USD(50)->lessThan(Money::USD(100)));
        static::assertFalse(Money::EUR(100)->lessThan(Money::EUR(100)));
    }

    public function test_less_than_or_equal()
    {
        static::assertTrue(Money::USD(100)->lessThanOrEqual(Money::USD(100)));
        static::assertTrue(Money::USD(50)->lessThanOrEqual(Money::USD(100)));
        static::assertFalse(Money::EUR(100)->lessThanOrEqual(Money::EUR(50)));
    }

    public function test_value_sign()
    {
        static::assertTrue(Money::USD(0)->isZero());
        static::assertTrue(Money::EUR(0)->isZero());
        static::assertTrue(Money::USD(25)->isPositive());
        static::assertTrue(Money::EUR(25)->isPositive());
        static::assertTrue(Money::USD(-25)->isNegative());
        static::assertTrue(Money::EUR(-25)->isNegative());
    }

    public function test_allocate_to()
    {
        static::assertEquals([Money::USD(5), Money::USD(5)], Money::USD(10)->allocateTo(2));
        static::assertEquals([Money::EUR(5), Money::EUR(5)], Money::EUR(10)->allocateTo(2));
    }

    public function test_call_undefined_method()
    {
        static::assertEquals(Money::USD(15), Money::USD(15)->undefined());
    }

    public function test_getters()
    {
        $money = new Money(100, new Currency('USD'));
        $actual = ['amount' => '100', 'currency' => 'USD', 'formatted' => '$1.00'];

        static::assertInstanceOf(\Money\Money::class, $money->getMoney());
        static::assertJson($money->toJson());
        static::assertEquals($money->toArray(), $actual);
        static::assertEquals($money->jsonSerialize(), $actual);
        static::assertEquals('$1.00', $money->render());
        static::assertEquals('$1.00', $money);
    }

    public function test_macroable()
    {
        Money::macro('someMacro', function () {
            return 'some-return-value';
        });

        $money = new Money(100, new Currency('USD'));

        static::assertEquals(
            'some-return-value',
            $money->someMacro()
        );
    }
}
