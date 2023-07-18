import React, { useId } from 'react';
import clsx from 'clsx';

import Table from '../Shared/Table.jsx';

const DetailedResults = ({ results = [], className = '' }) => {
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
					{results.map(result => {
						const markers = result.markers;

						return (
							<li
								key={result.competence}
								className='detailed-rating-results__item'
							>
								<h3 className='title title--tiny detailed-rating-results__caption'>
									{result.competence}
								</h3>
								<Table
									columns={markers.columns}
									data={markers.data}
									placeholder='?'
								/>
							</li>
						);
					})}
				</ul>
			) : null}
		</section>
	);
};

export default DetailedResults;
