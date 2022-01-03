<?php

namespace FmTod\Money\Tests\Feature;

use FmTod\Money\Money;
use FmTod\Money\Serializers\DecimalSerializer;
use FmTod\Money\Tests\TestCase;

class MoneySerializerTest extends TestCase
{
    public function testDefaultSerializer() {
        static::assertEquals([
            'amount' => '100',
            'currency' => 'USD',
            'formatted' => '$1.00',
        ], Money::USD(100)->jsonSerialize());
    }

    public function testDecimalSerializer() {
        config()->set('money.serializer', DecimalSerializer::class);

        static::assertEquals([
            'value' => '1.00',
            'currency' => 'USD'
        ], Money::USD(100)->jsonSerialize());
    }
}
