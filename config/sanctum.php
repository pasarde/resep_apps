<?php

use Laravel\Sanctum\Sanctum;

return [
    'stateful' => [
        'localhost',
        '127.0.0.1',
        '127.0.0.1:8000',
        'localhost:3000',
    ],

    'guard' => [], // Hapus web guard, biar langsung pake bearer token

    'expiration' => 60, // Tambah expiration (opsional, bisa dihapus kalo masih tes)

    'token_prefix' => env('SANCTUM_TOKEN_PREFIX', ''),

    'middleware' => [
        'verify_csrf_token' => App\Http\Middleware\VerifyCsrfToken::class,
        'authenticate' => 'auth:sanctum',
    ],
];