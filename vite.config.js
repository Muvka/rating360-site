import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';
import createSvgSpritePlugin from 'vite-plugin-svg-sprite';

export default ({ mode }) => {
	process.env = { ...process.env, ...loadEnv(mode, process.cwd()) };
	const host = process.env.VITE_DEV_HOST;
	const certificatePath = process.env.VITE_DEV_CERTIFICATE_PATH;

	const config = defineConfig({
		plugins: [
			laravel({
				input: ['resources/css/app.scss', 'resources/js/app.jsx'],
				refresh: true
			}),
			createSvgSpritePlugin({
				include: '**/icon-*.svg',
				symbolId: '[name]-[hash]'
			})
		],
		resolve: {
			alias: {
				'@': '/resources'
			}
		}
	});

	if (host && certificatePath) {
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
