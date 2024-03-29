import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';
import manifestSRI from 'vite-plugin-manifest-sri';
import { viteStaticCopy } from 'vite-plugin-static-copy'

export default defineConfig({
    optimizeDeps: {
        entries: './resources/css/app.css',
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
            ],
            refresh: true,
        }),
        manifestSRI(),
        viteStaticCopy({
            targets: [
                {
                    src: 'node_modules/@fortawesome/fontawesome-free/js/all.min.js',
                    dest: 'js',
                    rename: 'fontawesome.min.js',
                }
            ]
        }),
    ],
});
