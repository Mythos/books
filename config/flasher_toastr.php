<?php

return [
    'scripts' => [
        config('app.url') . '/js/flasher-toastr.min.js',
    ],
    'styles' => [],
    'options' => [
        'progressBar' => true,
        'timeOut' => 5000,
        'positionClass' => 'toast-bottom-right',
    ],
];
