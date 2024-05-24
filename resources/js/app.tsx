import React from 'react';
import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

import DefaultLayout from './Layouts/DefaultLayout.jsx';
import { ModalNotificationProvider } from '@js/Components/Shared/modal-notification';

function setupPageLayout(module: any) {
	if (!module.default.layout) {
		module.default.layout = (page: React.ReactNode) => (
			<DefaultLayout children={page}></DefaultLayout>
		);
	}
}

createInertiaApp({
	resolve: name => {
		const page: any = resolvePageComponent(
			`./Pages/${name}.jsx`,
			import.meta.glob('./Pages/**/*.jsx')
		);
		page.then((module: any) => setupPageLayout(module));

		return page;
	},
	title: title => `${title} - Оценка 360`,
	progress: {
		delay: 0,
		color: '#5465FF',
		includeCSS: true,
		showSpinner: false
	},
	setup({ el, App, props }) {
		el.classList.add('page__wrapper');
		createRoot(el).render(
			<ModalNotificationProvider>
				<App {...props} />
			</ModalNotificationProvider>
		);
	}
});
