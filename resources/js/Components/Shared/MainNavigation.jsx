import React from 'react';
import { Link, usePage } from '@inertiajs/react';
import clsx from 'clsx';

import moreCircleIconId from '../../../images/shared/icons/icon-more-circle.svg';
import documentIconId from '../../../images/shared/icons/icon-document.svg';
import usersIconId from '../../../images/shared/icons/icon-users.svg';
import briefcaseIconId from '../../../images/shared/icons/icon-briefcase.svg';
import activityIconId from '../../../images/shared/icons/icon-activity.svg';
import graphIconId from '../../../images/shared/icons/icon-graph.svg';
import gridIconId from '../../../images/shared/icons/icon-grid.svg';

const icons = {
	moreCircle: moreCircleIconId,
	document: documentIconId,
	users: usersIconId,
	briefcase: briefcaseIconId,
	activity: activityIconId,
	graph: graphIconId,
	grid: gridIconId
};

const MainNavigation = ({ className = '' }) => {
	const navigationItems = usePage().props?.shared?.navigation?.main ?? [];

	if (!navigationItems.length) return false;

	return (
		<nav className={clsx('main-navigation', className)} aria-label='Главная'>
			<ul className='main-navigation__list'>
				{navigationItems.map(navigationItem => {
					return (
						<li
							key={navigationItem.label}
							className={clsx('main-navigation__item', {
								'main-navigation__item--separated': navigationItem.separate
							})}
						>
							<Link
								href={navigationItem.href}
								className={clsx('main-navigation__link', {
									'main-navigation__link--current': navigationItem.isCurrent
								})}
							>
								{Boolean(navigationItem.icon) && (
									<svg
										width='24'
										height='24'
										className='main-navigation__icon'
										aria-hidden='true'
									>
										<use xlinkHref={`#${icons[navigationItem.icon]}`} />
									</svg>
								)}
								{navigationItem.label}
							</Link>
						</li>
					);
				})}
			</ul>
		</nav>
	);
};

export default MainNavigation;
