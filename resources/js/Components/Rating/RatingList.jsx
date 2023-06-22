import React, { useId } from 'react';
import clsx from 'clsx';

import RatingLink from './RatingLink.jsx';

const RatingList = ({ ratings = [], className = '' }) => {
	const titleId = useId();

	return (
		<section
			className={clsx('rating-list', className)}
			aria-labelledby={titleId}
		>
			<h2 id={titleId} className='title title--small rating-list__title'>
				Доступные оценки
			</h2>
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
