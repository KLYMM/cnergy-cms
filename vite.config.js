import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

const mainPath = ['resources/sass/app.scss', 'resources/js/app.js'];
const pages = [
    'resources/sass/pages/category-page.scss', 'resources/js/pages/categoryPage.js'
];

export default defineConfig({
    plugins: [
        laravel({
            input: [
                ...mainPath,
                ...pages,
            ],
            refresh: true,
        }),
    ],
});
