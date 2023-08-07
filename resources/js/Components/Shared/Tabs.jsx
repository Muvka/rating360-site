import React, { useMemo, createRef, useState } from 'react';

import Tab from './Tab.jsx';
import TabPanel from './TabPanel.jsx';

const Tabs = ({
	tabs: tabsProp = [],
	titleId = undefined,
	selectedTab: selectedTabProp = 0,
	tabPanelClassName = ''
}) => {
	const [selectedTab, setSelectedTab] = useState(selectedTabProp);
	const tabs = useMemo(() => {
		return tabsProp.map(tab => ({
			...tab,
			ref: createRef(),
			tabPanelId: `${tab.id}-panel`
		}));
	}, [tabsProp]);

	const changeTab = index => {
		setSelectedTab(index);
	};

	const handleKeyPress = event => {
		const tabCount = tabs.length;

		if (event.key === 'ArrowLeft') {
			event.preventDefault();
			const last = tabCount - 1;
			const next = selectedTab - 1;

			handleNextTab(last, next, 0);
		}
		if (event.key === 'ArrowRight') {
			event.preventDefault();
			const first = 0;
			const next = selectedTab + 1;

			handleNextTab(first, next, tabCount - 1);
		}
	};

	const handleNextTab = (firstTabInRound, nextTab, lastTabInRound) => {
		const tabToSelect =
			selectedTab === lastTabInRound ? firstTabInRound : nextTab;
		setSelectedTab(tabToSelect);
		tabs[tabToSelect].ref.current.focus();
	};

	return (
		<div className='tabs'>
			<div className='tabs__tablist-container'>
				<div
					className='tabs__tablist'
					role='tablist'
					aria-labelledby={titleId}
					onKeyDown={handleKeyPress}
				>
					{tabs.map((tab, index) => {
						return (
							<Tab
								key={tab.id}
								id={tab.id}
								tabPanelId={tab.tabPanelId}
								index={index}
								onClick={changeTab}
								selectedTab={selectedTab}
								tabRef={tab.ref}
							>
								{tab.title}
							</Tab>
						);
					})}
				</div>
			</div>
			{tabs.map((tab, index) => {
				return (
					<TabPanel
						key={tab.id}
						id={tab.tabPanelId}
						tabId={tab.id}
						index={index}
						selectedTab={selectedTab}
						className={tabPanelClassName}
					>
						{tab.content}
					</TabPanel>
				);
			})}
		</div>
	);
};

export default Tabs;
