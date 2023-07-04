import React from 'react';
import { Link, usePage } from '@inertiajs/react';
import clsx from 'clsx';

import logotypeImage from '../../../images/shared/logotype.svg';

const Logotype = ({ className = '' }) => {
	const { url, props = {} } = usePage();

	return (
		<div className={clsx('logotype', className)}>
			<img
				src={logotypeImage}
				alt=''
				width='46'
				height='46'
				className='logotype__image'
			/>
			<Link
				href={route('client.rating.ratings.index')}
				className={clsx('logotype__link', {
					'logotype__link--inactive': url === '/'
				})}
			>
				{props.shared?.app?.name}
			</Link>
		</div>
	);
};

export default Logotype;
