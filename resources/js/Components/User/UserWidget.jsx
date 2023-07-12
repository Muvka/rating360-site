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
			<a
				href='https://edu.zhcom.ru/my'
				className='user-widget__link'
				rel='nofollow noopener noreferrer'
			>
				Учебный портал
			</a>
		</div>
	);
};

export default UserWidget;
