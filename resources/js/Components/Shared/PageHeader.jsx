import React from 'react';
import clsx from 'clsx';

import Logotype from './Logotype.jsx';
import UserWidget from '../User/UserWidget.jsx';
import MainMenu from './MainMenu.jsx';
import SecondaryNavigation from './SecondaryNavigation.jsx';

const PageHeader = ({ className = '' }) => {
	return (
		<header className={clsx('page-header', className)}>
			<div className='page-header__container container'>
				<Logotype className='page-header__logotype' />
				<MainMenu>
					<SecondaryNavigation className='main-menu__navigation' />
					<UserWidget className='main-menu__user' />
				</MainMenu>
			</div>
		</header>
	);
};

export default PageHeader;
