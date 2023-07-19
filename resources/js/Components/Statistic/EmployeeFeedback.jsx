import React from 'react';
import clsx from 'clsx';

const EmployeeFeedback = ({ data = {}, className = '' }) => {
	if (!Object.keys(data).length) return false;

	return (
		<section className={clsx('employee-feedback', className)}>
			<h2 className='title title--small employee-feedback__title'>
				Обратная связь
			</h2>
			<p className='text employee-feedback__description'>
				Отзывы, пожелания, замечания от коллег:
			</p>
			<div className='employee-feedback__grid'>
				{Object.entries(data).map(([key, value], index) => (
					<div key={index.toString()} className='employee-feedback__block'>
						<h3 className='title title--tiny employee-feedback__caption'>
							{key}
						</h3>
						<ul className='employee-feedback__list'>
							{value.map(item => (
								<li key={item.id} className='text'>
									{item.text}
								</li>
							))}
						</ul>
					</div>
				))}
			</div>
		</section>
	);
};

export default EmployeeFeedback;
