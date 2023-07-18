import React, { useId } from 'react';
import clsx from 'clsx';

import CompetencyResultItem from './CompetencyResultItem.jsx';

const CompetencyResultList = ({ results = [], className = '' }) => {
	const titleId = useId();

	if (!results.length) {
		return false;
	}

	return (
		<section
			className={clsx('rating-result-list', className)}
			aria-labelledby={titleId}
		>
			<h2 id={titleId} className='visually-hidden'>
				Результаты оценки по компетенциям
			</h2>
			<ul className='rating-result-list__list'>
				{results.map(result => (
					<CompetencyResultItem
						key={result.competence}
						name={result.competence}
						averageRating={result.averageRating}
						averageRatingWithoutSelf={result.averageRatingWithoutSelf}
					/>
				))}
			</ul>
		</section>
	);
};

export default CompetencyResultList;
