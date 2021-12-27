<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Laravel money
     |--------------------------------------------------------------------------
     */
    'locale' => config('app.locale', 'en_US'),
    'currency' => config('app.currency', 'USD'),
    'serializer' => FmTod\Money\Serializers\DefaultSerializer::class,
    'formatter' => null,
    'cast' => null,
    'currencies' => [
        'iso' => 'all',
        'bitcoin' => 'all',
        'custom' => [
            // 'MY1' => 2,
            // 'MY2' => 3
        ],
    ],
];
