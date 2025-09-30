<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PHP Error Reporting Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains PHP error reporting settings to suppress deprecation
    | warnings that are corrupting JSON responses.
    |
    */

    'error_reporting' => E_ALL & ~E_DEPRECATED & ~E_STRICT,
    'display_errors' => false,
    'log_errors' => true,
];