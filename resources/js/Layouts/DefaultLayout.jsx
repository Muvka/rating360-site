import React from 'react';
import { Head, usePage } from '@inertiajs/react';
import { Toaster } from 'react-hot-toast';

import PageHeader from '../Components/Shared/PageHeader';
import PageSidebar from '../Components/Shared/PageSidebar.jsx';

const DefaultLayout = ({ children }) => {
	const { logotype } = usePage().props?.shared?.app ?? {};

	return (
		<>
			<Head>
				<link rel='icon' href={logotype} />
			</Head>
			<PageHeader className='page__header' />
			<div className='page__grid container'>
				<PageSidebar className='background-box page__sidebar' />
				<main className='page-content page-content--with-padding background-box page__content page__content--fluid'>
					{children}
				</main>
			</div>
			<Toaster
				toastOptions={{
					className: 'toast',
					duration: 5000
				}}
			/>
		</>
	);
};

export default DefaultLayout;
