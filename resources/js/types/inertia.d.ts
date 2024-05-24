import { PageProps as InertiaPageProps } from '@inertiajs/core';

type NavigationItemData = {
	text: string;
	href: string;
	icon?: string;
	isCurrent?: boolean;
	separate?: boolean;
}

export type PageProps = InertiaPageProps & {
	shared: {
		app: {
			name: string;
            logotype?: string;
		};
		auth: {
			user?: {
				full_name: string;
			};
		};
		navigation: {
			primary: Array<NavigationItemData>;
			secondary: Array<NavigationItemData>;
		};
	}
};
