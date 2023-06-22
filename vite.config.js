import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';
import createSvgSpritePlugin from 'vite-plugin-svg-sprite';

const host = 'rating360.loc';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.scss', 'resources/js/app.jsx'],
            refresh: true,
        }),
        createSvgSpritePlugin({
            include: '**/icon-*.svg',
            symbolId: '[name]-[hash]',
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
