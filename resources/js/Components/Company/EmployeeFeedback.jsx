import React from 'react';
import clsx from 'clsx';

const EmployeeFeedback = ({
	positives = [],
	negatives = [],
	className = ''
}) => {
	return (
		<section className={clsx('employee-feedback', className)}>
			<h2 className='title title--small employee-feedback__title'>
				Обратная связь
			</h2>
			<p className='text employee-feedback__description'>
				Отзывы, пожелания, замечания от коллег:
			</p>
			<div className='employee-feedback__grid'>
				{Boolean(positives.length) && (
					<div className='employee-feedback__block employee-feedback__block--positive'>
						<h3 className='title title--tiny employee-feedback__caption'>
							Что получается
						</h3>
						<ul className='employee-feedback__list'>
							{positives.map((positive, index) => (
								<li key={index.toString()} className='text'>
									{positive}
								</li>
							))}
						</ul>
					</div>
				)}
				{Boolean(negatives.length) && (
					<div className='employee-feedback__block employee-feedback__block--negative'>
						<h3 className='title title--tiny employee-feedback__caption'>
							Области развития
						</h3>
						<ul className='employee-feedback__list'>
							{negatives.map((negative, index) => (
								<li key={index.toString()} className='text'>
									{negative}
								</li>
							))}
						</ul>
					</div>
				)}
			</div>
		</section>
	);
};

export default EmployeeFeedback;
