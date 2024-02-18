import React from 'react';
import { Head, usePage } from '@inertiajs/react';
import { Toaster } from 'react-hot-toast';

const BlankLayout = ({ children }) => {
	const { logotype } = usePage().props?.shared?.app ?? {};

	return (
		<>
			<Head>
				<link rel='icon' href={logotype} />
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

export default BlankLayout;
