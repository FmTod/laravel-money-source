<?php

namespace FmTod\Money\Tests\Feature;

use FmTod\Money\Blade\BladeExtension;
use FmTod\Money\Tests\TestCase;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Mockery;

class BladeExtensionTest extends TestCase
{
    protected BladeCompiler $compiler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->compiler = new BladeCompiler(Mockery::mock(Filesystem::class), __DIR__);

        BladeExtension::register($this->compiler);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_currency()
    {
        static::assertEquals(
            '<?php echo currency("USD"); ?>',
            $this->compiler->compileString('@currency("USD")')
        );
    }

    public function test_money()
    {
        static::assertEquals(
            '<?php echo money(500); ?>',
            $this->compiler->compileString('@money(500)')
        );

        static::assertEquals(
            '<?php echo money(500, "USD"); ?>',
            $this->compiler->compileString('@money(500, "USD")')
        );
    }

    public function test_money_min()
    {
        static::assertEquals(
            '<?php echo money_min(money(100), money(200), money(300)); ?>',
            $this->compiler->compileString('@money_min(money(100), money(200), money(300))')
        );

        static::assertEquals(
            '<?php echo money_min(money(100, "USD"), money(200, "USD"), money(300, "USD")); ?>',
            $this->compiler->compileString('@money_min(money(100, "USD"), money(200, "USD"), money(300, "USD"))')
        );
    }

    public function test_money_max()
    {
        static::assertEquals(
            '<?php echo money_max(money(100), money(200), money(300)); ?>',
            $this->compiler->compileString('@money_max(money(100), money(200), money(300))')
        );

        static::assertEquals(
            '<?php echo money_max(money(100, "USD"), money(200, "USD"), money(300, "USD")); ?>',
            $this->compiler->compileString('@money_max(money(100, "USD"), money(200, "USD"), money(300, "USD"))')
        );
    }

    public function test_money_avg()
    {
        static::assertEquals(
            '<?php echo money_avg(money(100), money(200), money(300)); ?>',
            $this->compiler->compileString('@money_avg(money(100), money(200), money(300))')
        );

        static::assertEquals(
            '<?php echo money_avg(money(100, "USD"), money(200, "USD"), money(300, "USD")); ?>',
            $this->compiler->compileString('@money_avg(money(100, "USD"), money(200, "USD"), money(300, "USD"))')
        );
    }

    public function test_money_sum()
    {
        static::assertEquals(
            '<?php echo money_sum(money(100), money(200), money(300)); ?>',
            $this->compiler->compileString('@money_sum(money(100), money(200), money(300))')
        );

        static::assertEquals(
            '<?php echo money_sum(money(100, "USD"), money(200, "USD"), money(300, "USD")); ?>',
            $this->compiler->compileString('@money_sum(money(100, "USD"), money(200, "USD"), money(300, "USD"))')
        );
    }

    public function test_money_parse()
    {
        static::assertEquals(
            '<?php echo money_parse("R$5,00"); ?>',
            $this->compiler->compileString('@money_parse("R$5,00")')
        );

        static::assertEquals(
            '<?php echo money_parse("$5.00", "USD"); ?>',
            $this->compiler->compileString('@money_parse("$5.00", "USD")')
        );
    }

    public function test_money_parse_by_bitcoin()
    {
        static::assertEquals(
            '<?php echo money_parse_by_bitcoin("\xC9\x831000.00"); ?>',
            $this->compiler->compileString('@money_parse_by_bitcoin("\xC9\x831000.00")')
        );

        static::assertEquals(
            '<?php echo money_parse_by_bitcoin("\xC9\x831000.00", null, 4); ?>',
            $this->compiler->compileString('@money_parse_by_bitcoin("\xC9\x831000.00", null, 4)')
        );
    }

    public function test_money_parse_by_decimal()
    {
        static::assertEquals(
            '<?php echo money_parse_by_decimal("5.00", "USD"); ?>',
            $this->compiler->compileString('@money_parse_by_decimal("5.00", "USD")')
        );

        static::assertEquals(
            '<?php echo money_parse_by_decimal("5.00", "USD", null); ?>',
            $this->compiler->compileString('@money_parse_by_decimal("5.00", "USD", null)')
        );
    }

    public function test_money_parse_by_intl()
    {
        static::assertEquals(
            '<?php echo money_parse_by_intl("R$5,00"); ?>',
            $this->compiler->compileString('@money_parse_by_intl("R$5,00")')
        );

        static::assertEquals(
            '<?php echo money_parse_by_intl("$5.00", "USD"); ?>',
            $this->compiler->compileString('@money_parse_by_intl("$5.00", "USD")')
        );

        static::assertEquals(
            '<?php echo money_parse_by_intl("$5.00", "USD", "en_US"); ?>',
            $this->compiler->compileString('@money_parse_by_intl("$5.00", "USD", "en_US")')
        );

        static::assertEquals(
            '<?php echo money_parse_by_intl("$5.00", "USD", "en_US", null); ?>',
            $this->compiler->compileString('@money_parse_by_intl("$5.00", "USD", "en_US", null)')
        );
    }

    public function test_money_parse_by_intl_localized_decimal()
    {
        static::assertEquals(
            '<?php echo money_parse_by_intl_localized_decimal("1.00", "USD"); ?>',
            $this->compiler->compileString('@money_parse_by_intl_localized_decimal("1.00", "USD")')
        );

        static::assertEquals(
            '<?php echo money_parse_by_intl_localized_decimal("5.00", "USD"); ?>',
            $this->compiler->compileString('@money_parse_by_intl_localized_decimal("5.00", "USD")')
        );

        static::assertEquals(
            '<?php echo money_parse_by_intl_localized_decimal("5.00", "USD", "en_US"); ?>',
            $this->compiler->compileString('@money_parse_by_intl_localized_decimal("5.00", "USD", "en_US")')
        );

        static::assertEquals(
            '<?php echo money_parse_by_intl_localized_decimal("5.00", "USD", "en_US", null); ?>',
            $this->compiler->compileString('@money_parse_by_intl_localized_decimal("5.00", "USD", "en_US", null)')
        );
    }
}
