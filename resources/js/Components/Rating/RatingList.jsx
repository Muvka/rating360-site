import React, { useId, useMemo } from 'react';
import clsx from 'clsx';

import RatingLink from './RatingLink.jsx';
import BadgeText from '../Shared/BadgeText.jsx';

const RatingList = ({ ratings = [], className = '' }) => {
	const titleId = useId();
	const progress = useMemo(() => {
		const completed = ratings.reduce((acc, rating) => {
			return rating.isCompleted ? ++acc : acc;
		}, 0);

		return ratings.length ? `Оценено ${completed} из ${ratings.length}` : '';
	}, [ratings]);

	return (
		<section
			className={clsx('rating-list', className)}
			aria-labelledby={titleId}
		>
			<header className='rating-list__header'>
				<h2 id={titleId} className='title title--small rating-list__title'>
					Доступные оценки
				</h2>
				<BadgeText text={progress} variant='accent' />
			</header>
			{ratings.length ? (
				<ul className='rating-list__list'>
					{ratings.map(rating => (
						<li key={rating.id} className='rating-list__item'>
							<RatingLink {...rating} />
						</li>
					))}
				</ul>
			) : (
				<p className='text rating-list__text'>Для Вас нет доступных оценок.</p>
			)}
		</section>
	);
};

export default RatingList;
