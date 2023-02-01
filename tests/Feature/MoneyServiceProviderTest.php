<?php

namespace FmTod\Money\Tests\Feature;

use FmTod\Money\Tests\TestCase;

class MoneyServiceProviderTest extends TestCase
{
    public function testBladeDirectives()
    {
        $customDirectives = $this->app->make('blade.compiler')->getCustomDirectives();

        static::assertArrayHasKey('money', $customDirectives);
        static::assertArrayHasKey('currency', $customDirectives);
    }
}
