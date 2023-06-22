import React from 'react';
import clsx from 'clsx';

import SubordinateItem from './SubordinateItem.jsx';

const SubordinateList = ({ subordinates = [], className = '' }) => {
	if (!subordinates.length) {
		return null;
	}

	return (
		<div className={clsx('subordinate-list', className)}>
			<ul className='subordinate-list__list'>
				{subordinates.map(subordinate => (
					<SubordinateItem
						key={subordinate.id}
						name={subordinate.name}
						href={subordinate.href}
					/>
				))}
			</ul>
		</div>
	);
};

export default SubordinateList;
