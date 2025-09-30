<?php
return [
    'paths' => [
        'api/*',
        'linkedin-callback',
        'logout',
    ],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    // Must be true to allow cookies cross-site
    'supports_credentials' => true,
];