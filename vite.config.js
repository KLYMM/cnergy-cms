import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
// import esmodule from "vite-plugin-esmodule";

const mainPath = ["resources/sass/init.scss", "resources/js/app.js"];
const components = [
    "resources/sass/components/image-uploader.scss",
    "resources/sass/components/tags-input.scss",
    "resources/js/components/handleSortable.js",
    "resources/js/components/imageUploader.js",
]
const pages = [
    "resources/sass/pages/photonews.scss",
    "resources/sass/pages/auth.scss",
    "resources/js/pages/menuSettings.js",
    "resources/js/pages/photoNewsUploader.js",
];

const vendor = [
    "resources/js/vendor/choices.js/public/assets/scripts/choices.js",
    "resources/js/vendor/toastify.js"
]

export default defineConfig({
    plugins: [
        laravel({
            input: [...mainPath, ...components, ...pages],
            refresh: true,
        }),
        // esmodule([{ 'choices.js': 'ch' }]),
    ],
});
