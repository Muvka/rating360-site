import React, { useId } from 'react';
import clsx from 'clsx';
import MarkerResultsTable from './MarkerResultsTable.jsx';

const DetailedRatingResults = ({ data = [], className = '' }) => {
	const titleId = useId();

	return (
		<section
			className={clsx('detailed-rating-results', className)}
			aria-labelledby={titleId}
		>
			<h2 id={titleId} className='visually-hidden'>
				Подробные результаты оценки
			</h2>
			{Boolean(data.length) ? (
				<ul className='detailed-rating-results__list'>
					{data.map(item => (
						<li key={item.id} className='detailed-rating-results__item'>
							<h3 className='title title--tiny detailed-rating-results__caption'>
								{item.competence}
							</h3>
							<MarkerResultsTable markers={item.markers} />
						</li>
					))}
				</ul>
			) : null}
		</section>
	);
};

export default DetailedRatingResults;
