import React, { useMemo } from 'react';
import clsx from 'clsx';

const RatingFormHeader = ({
	employee = '',
	blocksNumber = 0,
	step = 0,
	className = ''
}) => {
	const progressText = useMemo(() => {
		return `${((step / (blocksNumber - 1)) * 100).toFixed(0)}% (${
			step + 1
		} из ${blocksNumber})`;
	}, [step, blocksNumber]);

	return (
		<header className={clsx('rating-form__header', className)}>
			{Boolean(employee) && (
				<p className='title title--small rating-form__description'>
					Я считаю, что.. {employee}
				</p>
			)}
			<div
				className='rating-form__progress'
				style={{ '--progress-scale': step / (blocksNumber - 1) }}
			>
				<span className='rating-form__progress-text'>{progressText}</span>
			</div>
		</header>
	);
};

export default RatingFormHeader;
