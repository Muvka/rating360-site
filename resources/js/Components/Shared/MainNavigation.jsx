import React from 'react';
import { Link } from '@inertiajs/react';
import clsx from 'clsx';
import { v4 as uuidv4 } from 'uuid';

import moreCircleIconId from '../../../images/shared/icons/icon-more-circle.svg';
import documentIconId from '../../../images/shared/icons/icon-document.svg';
import usersIconId from '../../../images/shared/icons/icon-users.svg';

const navigationItems = [
	{
		id: uuidv4(),
		icon: moreCircleIconId,
		label: 'Доступные оценки',
		route: 'client.shared.home'
	},
	{
		id: uuidv4(),
		icon: documentIconId,
		label: 'Мой отчёт',
		route: 'client.shared.report'
	},
	{
		id: uuidv4(),
		icon: usersIconId,
		label: 'Результаты сотрудников',
		route: 'client.shared.test2'
	}
];

const MainNavigation = ({ className = '' }) => {
	return (
		<nav className={clsx('main-navigation', className)} aria-label='Главная'>
			<ul className='main-navigation__list'>
				{navigationItems.map(navigationItem => (
					<li key={navigationItem.id} className='main-navigation__item'>
						<Link
							href={route(navigationItem.route)}
							className={clsx('main-navigation__link', {
								'main-navigation__link--current': route().current(
									navigationItem.route
								)
							})}
						>
							{Boolean(navigationItem.icon) && (
								<svg
									width='24'
									height='24'
									className='main-navigation__icon'
									aria-hidden='true'
								>
									<use xlinkHref={`#${navigationItem.icon}`} />
								</svg>
							)}
							{navigationItem.label}
						</Link>
					</li>
				))}
			</ul>
		</nav>
	);
};

export default MainNavigation;
