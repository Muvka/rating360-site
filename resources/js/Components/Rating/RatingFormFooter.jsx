import React from 'react';
import clsx from 'clsx';

import { Button, ButtonVariant } from '@js/Components/Shared/buttons/button/';
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
				<Button
					type='button'
					disabled={processing}
					variant={ButtonVariant.Secondary}
					className='rating-form__button'
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
				</Button>
			)}
			{!showSubmitButton && (
				<Button
					type='button'
					className='rating-form__button rating-form__button--right'
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
				</Button>
			)}
			{showSubmitButton && (
				<Button
					type='submit'
					disabled={processing}
					className='rating-form__button rating-form__button--right'
				>
					Завершить
				</Button>
			)}
		</footer>
	);
};

export default RatingFormFooter;
