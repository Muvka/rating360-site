import React from 'react';
import { Head } from '@inertiajs/react';

import PageHeader from '../Components/Shared/PageHeader';
import PageSidebar from '../Components/Shared/PageSidebar.jsx';

const DefaultLayout = ({ children }) => {
	return (
		<>
			<Head>
				{/*<link rel='apple-touch-icon' sizes='180x180' href='/apple-touch-icon.png' />*/}
				{/*<link rel='icon' type='image/png' sizes='32x32' href='/favicon-32x32.png' />*/}
				{/*<link rel='icon' type='image/png' sizes='16x16' href='/favicon-16x16.png' />*/}
				{/*<link rel='manifest' href='/site.webmanifest' />*/}
			</Head>
			<PageHeader className='page__header' />
			<div className='page__grid container'>
				<PageSidebar className='background-box page__sidebar' />
				<main className='page-content background-box page__content'>
					{children}
				</main>
			</div>
		</>
	);
};

export default DefaultLayout;
