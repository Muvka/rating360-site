import React, { useId } from 'react';
import clsx from 'clsx';

import { ICompetencyResultListProps } from './types.ts';
import { CompetencyResultItem } from '@js/Components/Statistic/competency-result-item';
import './styles.scss';

export const CompetencyResultList = ({
	results,
	className
}: ICompetencyResultListProps) => {
	const titleId = useId();

	if (!results.length) {
		return false;
	}

	return (
		<section
			className={clsx('competency-result-list', className)}
			aria-labelledby={titleId}
		>
			<h2 id={titleId} className='visually-hidden'>
				Результаты оценки по компетенциям
			</h2>
			<ul className='competency-result-list__list'>
				{results.map(result => (
					<CompetencyResultItem
						key={result.name}
						name={result.name}
						description={result.description}
						averageRating={result.averageRating}
						averageRatingWithoutSelf={result.averageRatingWithoutSelf}
					/>
				))}
			</ul>
		</section>
	);
};
