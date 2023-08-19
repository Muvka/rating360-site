import React from 'react';
import { usePage } from '@inertiajs/react';
import clsx from 'clsx';

const UserWidget = ({ className = '' }) => {
	const { user = null } = usePage().props?.shared?.auth;

	if (!user) {
		return false;
	}

	return <p className={clsx('user-widget', className)}>{user.full_name}</p>;
};

export default UserWidget;
