import React, { useMemo } from 'react';
import clsx from 'clsx';

import RatingValue from './RatingValue.jsx';

const RatingResultBlock = ({
	competence = '',
	averageRatingByClient = {},
	averageRating = 0,
	averageRatingWithoutSelf = 0,
	className = ''
}) => {
	const clientData = useMemo(() => {
		if (!Object.keys(averageRatingByClient).length) return [];

		return [
			{
				id: 'outer',
				label: 'Оценка внешних клиентов',
				rating: averageRatingByClient.outer
			},
			{
				id: 'inner',
				label: 'Оценка внутренних клиентов',
				rating: averageRatingByClient.inner
			},
			{
				id: 'manager',
				label: 'Оценка руководителя',
				rating: averageRatingByClient.manager
			},
			{
				id: 'self',
				label: 'Самооценка',
				rating: averageRatingByClient.self
			}
		];
	}, [averageRatingByClient]);

	return (
		<li className={clsx('rating-result-block', className)}>
			<header className='rating-result-block__header'>
				<h3 className='title title--tiny rating-result-block__competence'>
					{competence}
				</h3>
				<RatingValue
					value={averageRating}
					extraValue={averageRatingWithoutSelf}
				/>
			</header>
			{Boolean(clientData.length) && (
				<ul className='rating-result-block__list'>
					{clientData.map(client => (
						<li key={client.id} className='rating-result-block__item'>
							<p className='rating-result-block__text'>
								{client.label}
								<span className='rating-result-block__rating'>
									{client.rating} из 5
								</span>
							</p>
							<div
								className='rating-result-block__progress'
								style={{
									'--progress-scale': `${client.rating / 5}`
								}}
							/>
						</li>
					))}
				</ul>
			)}
		</li>
	);
};

export default RatingResultBlock;
