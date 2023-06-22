import React, { useId } from 'react';
import { Link } from '@inertiajs/react';
import clsx from 'clsx';

import arrowUpRightIconId from '../../../images/shared/icons/icon-arrow-up-right.svg';

const SubordinateItem = ({ name = '', href = '', className = '' }) => {
	const buttonLabelId = useId();

	return (
		<li className={clsx('subordinate-item', className)}>
			{name}
			<Link href={href} className='subordinate-item__button'>
				<svg
					width='16'
					height='16'
					className='subordinate-item__icon'
					aria-labelledby={buttonLabelId}
				>
					<use xlinkHref={`#${arrowUpRightIconId}`} />
				</svg>
				<span id={buttonLabelId} className='subordinate-item__label'>
					Смотреть отчёт
				</span>
			</Link>
		</li>
	);
};

export default SubordinateItem;
