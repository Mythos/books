const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
const options = {
    postCss: [
        require('postcss-discard-comments')({
            removeAll: true
        })
    ],
    uglify: {
        uglifyOptions: {
            comments: false
        },
    }
};
mix.options(options)
    .js("resources/js/app.js", "public/js")
    .sass("resources/sass/app.scss", "public/css")
    .copy(
        "node_modules/@flasher/flasher/dist/flasher.min.js",
        "public/js/flasher.min.js"
    )
    .copy(
        "node_modules/@flasher/flasher-toastr/dist/flasher-toastr.min.js",
        "public/js/flasher-toastr.min.js"
    )
    .copy([
        'resources/js/service-worker.js',
    ], 'public/service-worker.js')
    .copy("resources/fonts", "public/fonts")
    .copy('resources/favicon/site.webmanifest', 'public/site.webmanifest')
    .copy('resources/favicon/android-chrome-192x192.png', 'public/android-chrome-192x192.png')
    .copy('resources/favicon/android-chrome-512x512.png', 'public/android-chrome-512x512.png')
    .copy('resources/favicon/apple-touch-icon.png', 'public/apple-touch-icon.png')
    .copy('resources/favicon/favicon.ico', 'public/favicon.ico')
    .copy('resources/favicon/favicon-16x16.png', 'public/favicon-16x16.png')
    .copy('resources/favicon/favicon-32x32.png', 'public/favicon-32x32.png')
    .sourceMaps()
    .version();
