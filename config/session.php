<?php

use Illuminate\Support\Str;

return [
'domain' => env('SESSION_DOMAIN', 'localhost'),
'secure' => env('SESSION_SECURE_COOKIE', false),
'http_only' => true,
'same_site' => 'lax',
];
