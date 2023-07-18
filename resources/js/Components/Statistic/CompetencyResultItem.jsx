import React from 'react';
import clsx from 'clsx';

import RatingValue from '../Rating/RatingValue.jsx';

const CompetencyResultItem = ({
	name = '',
	averageRating = 0,
	averageRatingWithoutSelf = 0,
	className = ''
}) => {
	return (
		<li className={clsx('rating-result-item', className)}>
			{name}{' '}
			<RatingValue
				value={averageRating}
				extraValue={averageRatingWithoutSelf}
				size='small'
			/>
		</li>
	);
};

export default CompetencyResultItem;
