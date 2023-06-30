import React from 'react';
import { Link, usePage } from '@inertiajs/react';
import clsx from 'clsx';

import moreCircleIconId from '../../../images/shared/icons/icon-more-circle.svg';
import documentIconId from '../../../images/shared/icons/icon-document.svg';
import usersIconId from '../../../images/shared/icons/icon-users.svg';

const MainNavigation = ({ className = '' }) => {
	const navigationItems = usePage().props?.shared?.navigation?.main ?? [];

	if (!navigationItems.length) return false;

	return (
		<nav className={clsx('main-navigation', className)} aria-label='Главная'>
			<ul className='main-navigation__list'>
				{navigationItems.map(navigationItem => {
					let icon = null;

					switch (navigationItem.id) {
						case 'home': {
							icon = moreCircleIconId;
							break;
						}

						case 'report': {
							icon = documentIconId;
							break;
						}

						case 'manager': {
							icon = usersIconId;
							break;
						}
					}

					return (
						<li key={navigationItem.id} className='main-navigation__item'>
							<Link
								href={navigationItem.href}
								className={clsx('main-navigation__link', {
									'main-navigation__link--current': navigationItem.isCurrent
								})}
							>
								{Boolean(icon) && (
									<svg
										width='24'
										height='24'
										className='main-navigation__icon'
										aria-hidden='true'
									>
										<use xlinkHref={`#${icon}`} />
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
