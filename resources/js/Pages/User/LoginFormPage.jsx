import React from 'react';
import { Head } from '@inertiajs/react';

import SimpleLayout from '../../Layouts/SimpleLayout.jsx';
import LoginForm from '../../Components/User/LoginForm.jsx';

const LoginFormPage = ({ title = '' }) => {
	return (
		<>
			<Head>
				<title>{title}</title>
			</Head>
			<LoginForm className='page-content__login-form' />
		</>
	);
};

LoginFormPage.layout = page => <SimpleLayout children={page} />;

export default LoginFormPage;
