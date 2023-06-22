import React from 'react';
import clsx from 'clsx';

import Logotype from './Logotype.jsx';
import UserWidget from '../User/UserWidget.jsx';

const PageHeader = ({ className = '' }) => {
	return (
		<header className={clsx('page-header', className)}>
			<div className='page-header__container container'>
				<Logotype className='page-header__logotype' />
				<UserWidget />
			</div>
		</header>
	);
};

export default PageHeader;
