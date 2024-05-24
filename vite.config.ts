import path from 'path';
import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import fs from 'fs';
import createSvgSpritePlugin from 'vite-plugin-svg-sprite';

export default ({ mode }: { mode: string }) => {
	process.env = { ...process.env, ...loadEnv(mode, process.cwd()) };
	const host = process.env.VITE_DEV_HOST;
	const certificatePath = process.env.VITE_DEV_CERTIFICATE_PATH;

	const config = defineConfig({
		plugins: [
			laravel({
				input: ['resources/css/app.scss', 'resources/js/app.tsx'],
				refresh: true
			}),
			react(),
			createSvgSpritePlugin({
				include: '**/icon-*.svg',
				symbolId: '[name]-[hash]'
			})
		],
		resolve: {
			alias: {
				'@': '/resources',
				'@js': path.resolve(__dirname, './resources/js'),
				'@icons': path.resolve(__dirname, './resources/images/shared/icons')
			}
		}
	});

	if (host && certificatePath) {
		// @ts-ignore
		config.server = {
			host,
			hmr: { host },
			https: {
				key: fs.readFileSync(`${certificatePath}/${host}.key`),
				cert: fs.readFileSync(`${certificatePath}/${host}.crt`)
			}
		};
	}

	return config;
};
