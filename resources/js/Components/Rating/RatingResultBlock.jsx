import React from 'react';
import clsx from 'clsx';

import RatingValue from './RatingValue.jsx';

const RatingResultBlock = ({
	competence = '',
	averageRatingByClient = {},
	averageRating = 0,
	averageRatingWithoutSelf = 0,
	className = ''
}) => {
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
			{Boolean(Object.keys(averageRatingByClient).length) && (
				<ul className='rating-result-block__list'>
					<li className='rating-result-block__item'>
						<p className='rating-result-block__text'>
							Оценка внешних клиентов
							<span className='rating-result-block__rating'>
								{averageRatingByClient.outer.toFixed(1)} из 5
							</span>
						</p>
						<div
							className='rating-result-block__progress'
							style={{
								'--progress-scale': `${averageRatingByClient.outer / 5}`
							}}
						/>
					</li>
					<li className='rating-result-block__item'>
						<p className='rating-result-block__text'>
							Оценка внутренних клиентов
							<span className='rating-result-block__rating'>
								{averageRatingByClient.inner.toFixed(1)} из 5
							</span>
						</p>
						<div
							className='rating-result-block__progress'
							style={{
								'--progress-scale': `${averageRatingByClient.inner / 5}`
							}}
						/>
					</li>
					<li className='rating-result-block__item'>
						<p className='rating-result-block__text'>
							Оценка руководителя
							<span className='rating-result-block__rating'>
								{averageRatingByClient.manager.toFixed(1)} из 5
							</span>
						</p>
						<div
							className='rating-result-block__progress'
							style={{
								'--progress-scale': `${averageRatingByClient.manager / 5}`
							}}
						/>
					</li>
					<li className='rating-result-block__item'>
						<p className='rating-result-block__text'>
							Самооценка
							<span className='rating-result-block__rating'>
								{averageRatingByClient.self.toFixed(1)} из 5
							</span>
						</p>
						<div
							className='rating-result-block__progress'
							style={{
								'--progress-scale': `${averageRatingByClient.self / 5}`
							}}
						/>
					</li>
				</ul>
			)}
		</li>
	);
};

export default RatingResultBlock;
