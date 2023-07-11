import React from 'react';
import { Head } from '@inertiajs/react';
import { Toaster } from 'react-hot-toast';

import PageHeader from '../Components/Shared/PageHeader';

const DefaultLayout = ({ children }) => {
	return (
		<>
			<Head>
				{/*<link rel='apple-touch-icon' sizes='180x180' href='/apple-touch-icon.png' />*/}
				{/*<link rel='icon' type='image/png' sizes='32x32' href='/favicon-32x32.png' />*/}
				{/*<link rel='icon' type='image/png' sizes='16x16' href='/favicon-16x16.png' />*/}
				{/*<link rel='manifest' href='/site.webmanifest' />*/}
			</Head>
			<main className='page-content page__content page__content--center'>
				{children}
			</main>
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
