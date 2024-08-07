import React, { CSSProperties, useMemo } from 'react';
import clsx from 'clsx';

import { ICompetencyResultCardProps } from '@js/Components/Statistic/competency-result-card/types.ts';
import RatingValue from '@js/Components/Rating/RatingValue';
import './styles.scss';

export const CompetencyResultCard = ({
	competence,
	averageRatingByClient,
	averageRating,
	averageRatingWithoutSelf,
	className
}: ICompetencyResultCardProps) => {
	const clientData = useMemo(() => {
		if (!Object.keys(averageRatingByClient).length) return [];

		return [
			{
				id: 'outer',
				label: 'Оценка внешних клиентов',
				rating: averageRatingByClient.outer ?? 0
			},
			{
				id: 'inner',
				label: 'Оценка внутренних клиентов',
				rating: averageRatingByClient.inner ?? 0
			},
			{
				id: 'manager',
				label: 'Оценка руководителя',
				rating: averageRatingByClient.manager ?? 0
			},
			{
				id: 'self',
				label: 'Самооценка',
				rating: averageRatingByClient.self ?? 0
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
								style={
									{
										'--progress-scale': `${client.rating / 5}`
									} as CSSProperties
								}
							/>
						</li>
					))}
				</ul>
			)}
		</li>
	);
};
