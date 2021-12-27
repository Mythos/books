<?php

return [

    /*
    |--------------------------------------------------------------------------
    | NSFW
    |--------------------------------------------------------------------------
    |
    | Allows setting the pixelation and blurring values for NSFW images
    | See:  http://image.intervention.io/api/pixelate
    |       http://image.intervention.io/api/blur
    |
    */
    'nsfw' => [
        'pixelate' => env('IMAGES_NSFW_PIXELATE', 20),
        'blur' => env('IMAGES_NSFW_BLUR', 10),
    ],
];
