<?php

namespace FmTod\Money\Tests\Feature;

use FmTod\Money\Money;
use FmTod\Money\Tests\Database\Models\User;
use FmTod\Money\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Money\Exception\ParserException;
use Money\Money as BaseMoney;
use stdClass;

/**
 * The money cast test.
 */
class MoneyCastTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(dirname(__DIR__).'/Database/Migrations');

        Money::setCurrencies(config('money.currencies'));
    }

    public function testCastsMoneyWhenRetrievingCastedValues()
    {
        $user = new User([
            'currency' => 'AUD',
            'money' => 1234.56,
            'wage' => 50000,
            'debits' => null,
        ]);

        static::assertInstanceOf(Money::class, $user->money);
        static::assertInstanceOf(Money::class, $user->wage);
        static::assertNull($user->debits);

        static::assertSame('123456', $user->money->getAmount());
        static::assertSame('AUD', $user->money->getCurrency()->getCode());

        static::assertSame('5000000', $user->wage->getAmount());
        static::assertSame('EUR', $user->wage->getCurrency()->getCode());

        $user->debits = 100.99;

        static::assertSame('10099', $user->debits->getAmount());
        static::assertSame('USD', $user->debits->getCurrency()->getCode());

        $user->save();

        static::assertSame(1, $user->id);

        $this->assertDatabaseHas('users', [
            'id' => 1,
            'money' => '1234.56',
            'wage' => '50000.00',
            'debits' => '100.99',
            'currency' => 'AUD',
        ]);
    }

    public function testCastsMoneyWhenSettingCastedValues()
    {
        $user = new User([
            'currency' => 'CAD',
            'money' => 0,
            'wage' => '6500000',
            'debits' => null,
        ]);

        static::assertSame('0', $user->money->getAmount());
        static::assertSame('CAD', $user->money->getCurrency()->getCode());

        static::assertSame('650000000', $user->wage->getAmount());
        static::assertSame('EUR', $user->wage->getCurrency()->getCode());

        static::assertNull($user->debits);

        $user->money = new BaseMoney(10000, $user->money->getCurrency());

        static::assertSame('10000', $user->money->getAmount());

        $user->money = 100;
        $user->wage = 70500.19;
        $user->debits = '¥213860';

        static::assertSame('10000', $user->money->getAmount());
        static::assertSame('CAD', $user->money->getCurrency()->getCode());

        static::assertSame('7050019', $user->wage->getAmount());
        static::assertSame('EUR', $user->wage->getCurrency()->getCode());

        static::assertSame('21386000', $user->debits->getAmount());
        static::assertSame('USD', $user->debits->getCurrency()->getCode());

        $user->money = '100,000.22';

        static::assertSame('10000022', $user->money->getAmount());
        static::assertSame('CAD', $user->money->getCurrency()->getCode());

        $user->save();

        static::assertSame(1, $user->id);

        $this->assertDatabaseHas('users', [
            'id' => 1,
            'money' => '100000.22',
            'wage' => '70500.19',
            'debits' => '213860',
            'currency' => 'CAD',
        ]);
    }

    public function testFailsToSetInvalidMoney()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid data provided for FmTod\Money\Tests\Database\Models\User::$money');

        new User(['money' => new stdClass()]);
    }

    public function testFailsToParseInvalidMoney()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('Unable to parse abc');

        new User(['money' => 'abc']);
    }
}
