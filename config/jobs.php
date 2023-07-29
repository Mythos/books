<?php

return [

    'mangapassion_updater' => [
        'include_completed' => env('JOBS_MANGAPASSION_UPDATER_INCLUDE_COMPLETED', false),
        'include_canceled' => env('JOBS_MANGAPASSION_UPDATER_INCLUDE_CANCELED', false),
    ],

];
