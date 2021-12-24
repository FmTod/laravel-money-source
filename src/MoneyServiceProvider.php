<?php

namespace FmTod\Money;

use FmTod\Money\Blade\BladeExtension;
use Illuminate\View\Compilers\BladeCompiler;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MoneyServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-money')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->callAfterResolving(BladeCompiler::class, function ($blade) {
            BladeExtension::register($blade);
        });
    }
}
