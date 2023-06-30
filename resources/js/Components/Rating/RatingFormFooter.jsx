import React from 'react';
import clsx from 'clsx';

import arrowLeftIconId from '../../../images/shared/icons/icon-arrow-left.svg';
import arrowRightIconId from '../../../images/shared/icons/icon-arrow-right.svg';

const RatingFormFooter = ({
	step = 0,
	processing = false,
	showBackButton = false,
	showSubmitButton = false,
	className = '',
	changeStep = () => {}
}) => {
	return (
		<footer className={clsx('rating-form__footer', className)}>
			{showBackButton && (
				<button
					type='button'
					disabled={processing}
					className='button rating-form__button'
					onClick={() => changeStep(step - 1)}
				>
					<svg
						width='24'
						height='24'
						className='button__icon'
						aria-hidden='true'
					>
						<use xlinkHref={`#${arrowLeftIconId}`} />
					</svg>
					Назад
				</button>
			)}
			{!showSubmitButton && (
				<button
					type='button'
					className='button button--accent rating-form__button rating-form__button--right'
					onClick={() => changeStep(step + 1)}
				>
					Вперед
					<svg
						width='24'
						height='24'
						className='button__icon'
						aria-hidden='true'
					>
						<use xlinkHref={`#${arrowRightIconId}`} />
					</svg>
				</button>
			)}
			{showSubmitButton && (
				<button
					type='submit'
					disabled={processing}
					className='button button--accent rating-form__button rating-form__button--right'
				>
					Завершить
				</button>
			)}
		</footer>
	);
};

export default RatingFormFooter;
