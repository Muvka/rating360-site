import React from 'react';
import clsx from 'clsx';

import MainNavigation from './MainNavigation.jsx';

const PageSidebar = ({ className = '' }) => {
	return (
		<aside className={clsx('page-sidebar', className)}>
			<MainNavigation />
		</aside>
	);
};

export default PageSidebar;
