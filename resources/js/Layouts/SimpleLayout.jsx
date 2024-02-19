import React from 'react';
import { Head, usePage } from '@inertiajs/react';

import PageHeader from '../Components/Shared/PageHeader';

const SimpleLayout = ({ children }) => {
	const { logotype } = usePage().props?.shared?.app ?? {};

	return (
		<>
			<Head>
				<link rel='icon' href={logotype} />
			</Head>
			<PageHeader className='page__header' />
			<div className='page__container page__container--stretch container'>
				<main className='page-content page-content--with-padding page-content--center background-box page__content page__content--fluid'>
					{children}
				</main>
			</div>
		</>
	);
};

export default SimpleLayout;
