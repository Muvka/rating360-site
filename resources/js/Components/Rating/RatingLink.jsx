import React from 'react';
import { Link } from '@inertiajs/react';
import clsx from 'clsx';

import chevronRightIconId from '../../../images/shared/icons/icon-chevron-right.svg';

const RatingLink = ({
	title = '',
	href = '',
	isCompleted = false,
	className = ''
}) => {
	return (
		<Link
			href={href}
			className={clsx(
				'rating-link',
				{
					'rating-link--completed': isCompleted
				},
				className
			)}
		>
			{title}
			<span className='rating-link__icon-container'>
				<svg
					width='16'
					height='16'
					className='rating-link__icon'
					aria-hidden='true'
				>
					<use xlinkHref={`#${chevronRightIconId}`} />
				</svg>
			</span>
		</Link>
	);
};

export default RatingLink;
