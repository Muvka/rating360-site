import React from 'react';
import { usePage } from '@inertiajs/react';
import clsx from 'clsx';

const UserWidget = ({ className = '' }) => {
	const { user, portalUrl } = usePage().props?.shared?.auth;

	if (!user) {
		return false;
	}

	return (
		<div className={clsx('user-widget', className)}>
			<p className='user-widget__name'>{user.full_name}</p>
			<a
				href={portalUrl}
				className='user-widget__link'
				rel='nofollow noopener noreferrer'
			>
				Учебный портал
			</a>
		</div>
	);
};

export default UserWidget;
