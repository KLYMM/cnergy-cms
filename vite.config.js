import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
// import esmodule from "vite-plugin-esmodule";

const mainPath = ["resources/sass/app.scss", "resources/js/app.js"];
const pages = [
    "resources/sass/pages/category-page.scss",
    
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
