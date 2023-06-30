import React, { useId } from 'react';
import clsx from 'clsx';
import MarkerResultsTable from './MarkerResultsTable.jsx';

const DetailedRatingResults = ({ results = [], className = '' }) => {
	const titleId = useId();

	if (!results.length) return false;

	return (
		<section
			className={clsx('detailed-rating-results', className)}
			aria-labelledby={titleId}
		>
			<h2 id={titleId} className='visually-hidden'>
				Подробные результаты оценки
			</h2>
			{Boolean(results.length) ? (
				<ul className='detailed-rating-results__list'>
					{results.map(result => (
						<li
							key={result.competence}
							className='detailed-rating-results__item'
						>
							<h3 className='title title--tiny detailed-rating-results__caption'>
								{result.competence}
							</h3>
							<MarkerResultsTable markers={result.markers} />
						</li>
					))}
				</ul>
			) : null}
		</section>
	);
};

export default DetailedRatingResults;
