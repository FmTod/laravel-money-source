<?php

namespace FmTod\Money\Tests;

use FmTod\Money\Traits\LocaleTrait;
use GrahamCampbell\TestBench\AbstractPackageTestCase;

class LocaleTraitTest extends AbstractPackageTestCase
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
