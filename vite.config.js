import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';

const host = 'rating360.loc';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host,
        hmr: { host },
        https: {
            key: fs.readFileSync(`/Applications/MAMP/Library/OpenSSL/certs/${host}.key`),
            cert: fs.readFileSync(`/Applications/MAMP/Library/OpenSSL/certs/${host}.crt`),
        },
    },
});
