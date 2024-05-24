import React from 'react';
import { Link } from '@inertiajs/react';
import clsx from 'clsx';

import { Button } from '@js/Components/Shared/buttons/button/';

const EmptyMessage = ({ text = '', withButton = true, className = '' }) => {
	return (
		<div className={clsx('empty-message', className)}>
			<p className='text empty-message__text'>{text}</p>
			{withButton && (
				<Button
					href={route('client.rating.ratings.index')}
					component={Link}
					className='empty-message__button'
				>
					На главную
				</Button>
			)}
		</div>
	);
};

export default EmptyMessage;
