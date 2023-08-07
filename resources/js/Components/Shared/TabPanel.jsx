import React from 'react';
import clsx from 'clsx';

const TabPanel = ({
	children = null,
	id = undefined,
	tabId = undefined,
	index = 0,
	selectedTab = 0,
	className = ''
}) => {
	return (
		<div
			role='tabpanel'
			id={id}
			aria-labelledby={tabId}
			hidden={selectedTab !== index}
			tabIndex={0}
			className={clsx('tabs__panel', className)}
		>
			{children}
		</div>
	);
};

export default TabPanel;
