<?php

use Flasher\Prime\Configuration;

return Configuration::from([
    'default' => 'toastr',
    'main_script' => '/js/flasher.min.js',
    'styles' => [],
    'inject_assets' => true,
    'translate' => true,
    'excluded_paths' => [],
    'flash_bag' => [
        'success' => ['success'],
        'error' => ['error', 'danger'],
        'warning' => ['warning', 'alarm'],
        'info' => ['info', 'notice', 'alert'],
    ],
    'plugins' => [
        'toastr' => [
            'scripts' => [
                '/js/toastr.min.js',
                '/js/flasher-toastr.min.js',
            ],
            'styles' => [
                '/css/toastr.min.css',
            ],
        ],
    ],
]);
