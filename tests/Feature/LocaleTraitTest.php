<?php

namespace FmTod\Money\Tests\Feature;

use FmTod\Money\Tests\TestCase;
use FmTod\Money\Traits\LocaleTrait;

class LocaleTraitTest extends TestCase
{
    public function testGetLocale()
    {
        $mock = $this->getMockForTrait(LocaleTrait::class);

        static::assertEquals('en_US', $mock->getLocale());
    }

    public function testSetLocale()
    {
        $mock = $this->getMockForTrait(LocaleTrait::class);

        $mock->setLocale('fr_FR');

        static::assertEquals('fr_FR', $mock->getLocale());
    }
}
