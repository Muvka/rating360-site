import React, { useId } from 'react';
import clsx from 'clsx';

import { CompetencyResultCard } from '@js/Components/Statistic/competency-result-card';

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
					<CompetencyResultCard
						key={result.competence}
						competence={result.competence}
						description={result.description}
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
