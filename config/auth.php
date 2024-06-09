<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        'professional' => [
            'driver' => 'session',
            'provider' => 'professionals',
        ],

        'patient' => [
            'driver' => 'session',
            'provider' => 'patients',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],

        'professionals' => [
            'driver' => 'eloquent',
            'model' => App\Models\Professional::class,
        ],

        'patients' => [
            'driver' => 'eloquent',
            'model' => App\Models\Patient::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
