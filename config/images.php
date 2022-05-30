<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Image Type
    |--------------------------------------------------------------------------
    |
    | Image type which should be used
    | Possible values: jpg, png, gif, bmp, webp
    |
    */
    'type' => env('IMAGES_TYPE', 'jpg'),

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
