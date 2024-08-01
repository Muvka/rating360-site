import React from 'react';

import { ICompetencyResultItemProps } from './types.ts';
import RatingValue from '@js/Components/Rating/RatingValue';
import chevronDownIcon from './assets/icon-chevron-down.svg';
import './styles.scss';

export const CompetencyResultItem = ({
	name,
	description = 'Описание отсутствует',
	averageRating,
	averageRatingWithoutSelf,
	className
}: ICompetencyResultItemProps) => {
	return (
		<li className={className}>
			<details className='competency-result-item'>
				<summary className='competency-result-item__summary'>
					<span className='competency-result-item__name'>{name}</span>
					<RatingValue
						value={averageRating}
						extraValue={averageRatingWithoutSelf}
						size='small'
					/>
					<span
						className='competency-result-item__icon-container'
						aria-hidden='true'
					>
						<svg
							className='competency-result-item__icon'
							width='16'
							height='16'
						>
							<use xlinkHref={`#${chevronDownIcon}`} />
						</svg>
					</span>
				</summary>
				<p className='competency-result-item__description'>{description}</p>
			</details>
		</li>
	);
};
