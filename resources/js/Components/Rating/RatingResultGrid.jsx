import React, { useId } from 'react';
import clsx from 'clsx';

import RatingResultBlock from './RatingResultBlock.jsx';

const RatingResultGrid = ({ results = [], className = '' }) => {
	const titleId = useId();

	if (!results.length) {
		return false;
	}

	return (
		<section
			className={clsx('rating-result-grid', className)}
			aria-labelledby={titleId}
		>
			<h2 id={titleId} className='visually-hidden'>
				Сетка результатов оценки
			</h2>
			<ul className='rating-result-grid__list'>
				{results.map(result => (
					<RatingResultBlock
						key={result.competence}
						competence={result.competence}
						averageRatingByClient={result.ratings}
						averageRating={result.averageRating}
						averageRatingWithoutSelf={result.averageRatingWithoutSelf}
					/>
				))}
			</ul>
		</section>
	);
};

export default RatingResultGrid;
