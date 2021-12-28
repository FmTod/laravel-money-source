<?php

namespace FmTod\Money\Tests\Feature;

use FmTod\Money\Money;
use FmTod\Money\Serializers\DecimalSerializer;
use FmTod\Money\Serializers\FormatSerializer;
use FmTod\Money\Tests\TestCase;

class MoneySerializerTest extends TestCase
{
    public function testDecimalSerializer() {
        config()->set('money.serializer', DecimalSerializer::class);

        static::assertEquals('1.00', Money::USD(100)->jsonSerialize());
    }

    public function testFormatSerializer() {
        config()->set('money.serializer', FormatSerializer::class);

        static::assertEquals('$1.00', Money::USD(100)->jsonSerialize());
    }
}
