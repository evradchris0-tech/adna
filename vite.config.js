import { defineConfig } from 'vite';
import * as path from "path"
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/ajout_paroisse.scss',
                'resources/css/associations.scss',
                'resources/css/engagement.scss',
                'resources/css/index.scss',
                'resources/css/layout.scss',
                'resources/css/login.scss',
                'resources/css/paroisse.scss',
                'resources/css/roles.scss',
                'resources/css/settings.scss',
                'resources/css/style.scss',
                'resources/css/chart.scss',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            "@node_modules": path.resolve(__dirname, "./node_modules"),
            '$': 'jQuery',
            jquery: 'jQuery',
            'select2': 'Select2'
        },
    },
});
