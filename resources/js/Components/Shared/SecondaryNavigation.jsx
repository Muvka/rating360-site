import React from 'react';
import clsx from 'clsx';
import { usePage } from '@inertiajs/react';

const SecondaryNavigation = ({ className = '' }) => {
	const { secondary: items = [] } = usePage().props?.shared?.navigation;

	if (!items) return false;

	return (
		<ul className={clsx('secondary-navigation', className)}>
			{items.map(item => (
				<li key={item.id} className='secondary-navigation__item'>
					<a href={item.href} className='secondary-navigation__link'>
						{item.text}
					</a>
				</li>
			))}
		</ul>
	);
};

export default SecondaryNavigation;
