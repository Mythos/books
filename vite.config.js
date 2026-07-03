import { copyFileSync, cpSync, mkdirSync } from "node:fs";
import { dirname } from "node:path";
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

const copyTargets = [
    ["node_modules/@flasher/flasher/dist/flasher.min.js", "public/js/flasher.min.js"],
    ["node_modules/@flasher/flasher-toastr/dist/flasher-toastr.min.js", "public/js/flasher-toastr.min.js"],
    ["node_modules/toastr/build/toastr.min.js", "public/js/toastr.min.js"],
    ["node_modules/toastr/build/toastr.min.css", "public/css/toastr.min.css"],
    ["node_modules/@fortawesome/fontawesome-free/webfonts", "public/build/webfonts"],
    ["resources/js/service-worker.js", "public/service-worker.js"],
    ["resources/fonts", "public/fonts"],
    ["resources/favicon/site.webmanifest", "public/site.webmanifest"],
    ["resources/favicon/android-chrome-192x192.png", "public/android-chrome-192x192.png"],
    ["resources/favicon/android-chrome-512x512.png", "public/android-chrome-512x512.png"],
    ["resources/favicon/apple-touch-icon.png", "public/apple-touch-icon.png"],
    ["resources/favicon/favicon.ico", "public/favicon.ico"],
    ["resources/favicon/favicon-16x16.png", "public/favicon-16x16.png"],
    ["resources/favicon/favicon-32x32.png", "public/favicon-32x32.png"],
];

function copyPublicAssets() {
    for (const [from, to] of copyTargets) {
        mkdirSync(dirname(to), { recursive: true });

        if (from.includes(".")) {
            copyFileSync(from, to);
            continue;
        }

        cpSync(from, to, { recursive: true });
    }
}

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/sass/app.scss",
                "resources/js/app.js",
            ],
            refresh: true,
        }),
        {
            name: "books-copy-public-assets",
            closeBundle: copyPublicAssets,
        },
    ],
});
