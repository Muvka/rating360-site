import React from 'react';
import clsx from 'clsx';
import { usePage } from '@inertiajs/react';

const UserWidget = ({ className = '' }) => {
	const user = usePage().props?.shared?.auth?.user;

	if (!user) {
		return false;
	}

	return (
		<div className={clsx('user-widget', className)}>
			<p className='user-widget__name'>{user.full_name}</p>
		</div>
	);
};

export default UserWidget;
