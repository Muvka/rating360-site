import React from 'react';

const Tab = ({
	tabRef = null,
	children = null,
	id = undefined,
	selectedTab = 0,
	index = 0,
	tabPanelId = undefined,
	onClick = () => {}
}) => {
	return (
		<button
			role='tab'
			ref={tabRef}
			id={id}
			aria-selected={selectedTab === index}
			aria-controls={tabPanelId}
			tabIndex={selectedTab === index ? 0 : -1}
			className='tabs__button'
			onClick={() => onClick(index)}
		>
			{children}
		</button>
	);
};

export default Tab;
