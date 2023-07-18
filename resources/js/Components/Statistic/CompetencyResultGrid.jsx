import React, { useId } from 'react';
import clsx from 'clsx';

import CompetencyResultBlock from './CompetencyResultBlock.jsx';

const CompetencyResultGrid = ({ results = [], className = '' }) => {
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
				Результаты оценки по компетенциям и клиентам
			</h2>
			<ul className='rating-result-grid__list'>
				{results.map(result => (
					<CompetencyResultBlock
						key={result.competence}
						competence={result.competence}
						averageRatingByClient={result.clients}
						averageRating={result.averageRating}
						averageRatingWithoutSelf={result.averageRatingWithoutSelf}
					/>
				))}
			</ul>
		</section>
	);
};

export default CompetencyResultGrid;
