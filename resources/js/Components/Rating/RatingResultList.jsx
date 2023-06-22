import React, { useId } from 'react';
import clsx from 'clsx';

import RatingResultItem from './RatingResultItem.jsx';

const RatingResultList = ({ results = [], className = '' }) => {
	const titleId = useId();

	if (!results.length) {
		return null;
	}

	return (
		<section
			className={clsx('rating-result-list', className)}
			aria-labelledby={titleId}
		>
			<h2 id={titleId} className='visually-hidden'>
				Cписок результатов оценки
			</h2>
			<ul className='rating-result-list__list'>
				{results.map(result => (
					<RatingResultItem
						key={result.id}
						name={result.competence}
						averageRating={result.averageRating}
						averageRatingWithoutSelf={result.averageRatingWithoutSelf}
					/>
				))}
			</ul>
		</section>
	);
};

export default RatingResultList;
