import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
// import esmodule from "vite-plugin-esmodule";

const mainPath = ["resources/sass/app.scss", "resources/js/app.js"];
const pages = [
    "resources/sass/pages/auth.scss",
    "resources/sass/pages/input-category-page.scss",
    "resources/js/pages/categoryPage.js",
];

export default defineConfig({
    plugins: [
        laravel({
            input: [...mainPath, ...pages],
            refresh: true,
        }),
        // esmodule([{ 'bootstrap5-tagsinput': 'bootstrap5-tagsinput/tagsinput.js' }]),
    ],
});
