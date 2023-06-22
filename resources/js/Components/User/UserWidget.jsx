import React from 'react';
import clsx from 'clsx';
import { usePage } from '@inertiajs/react';

const UserWidget = ({ className = '' }) => {
	const user = usePage().props?.auth?.user;

	if (!user) {
		return null;
	}

	return (
		<div className={clsx('user-widget', className)}>
			<p className='user-widget__name'>{user.fullName}</p>
		</div>
	);
};

export default UserWidget;
