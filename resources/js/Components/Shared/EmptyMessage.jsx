import React from 'react';
import { Link } from '@inertiajs/react';
import clsx from 'clsx';

const EmptyMessage = ({ text = '', className = '' }) => {
	return (
		<div className={clsx('empty-message', className)}>
			<p className='text empty-message__text'>{text}</p>
			<Link
				href={route('client.rating.ratings.index')}
				className='button button--accent empty-message__button'
			>
				На главную
			</Link>
		</div>
	);
};

export default EmptyMessage;
