import React, { useMemo, useState } from 'react';
import { useForm } from '@inertiajs/react';
import clsx from 'clsx';

import RatingFormBlock from './RatingFormBlock.jsx';
import arrowLeftIconId from '../../../images/shared/icons/icon-arrow-left.svg';
import arrowRightIconId from '../../../images/shared/icons/icon-arrow-right.svg';

const RatingForm = ({ blocks = [], employeeName = '', className = '' }) => {
	const initialFormData = useMemo(() => {
		return blocks
			.map(block => {
				return block.markers;
			})
			.flat()
			.reduce((acc, marker) => {
				acc[`marker${marker.id}`] = '';
				return acc;
			}, {});
	}, [blocks]);
	const { data, setData, post, processing, errors } = useForm(initialFormData);
	const [step, setStep] = useState(0);
	const progressText = useMemo(() => {
		if (!blocks.length) return 0;

		return `${((step / blocks.length) * 100).toFixed(0)}% (${step + 1} из ${
			blocks.length
		})`;
	}, [step, blocks]);

	const submitHandler = event => {
		event.preventDefault();
	};

	if (!blocks.length) {
		return null;
	}

	return (
		<form className={clsx('rating-form', className)} onSubmit={submitHandler}>
			<header className='rating-form__header'>
				{Boolean(employeeName) && (
					<p className='title title--small rating-form__description'>
						Я считаю, что.. {employeeName}
					</p>
				)}
				<div
					className='rating-form__progress'
					style={{ '--progress-scale': step / blocks.length }}
				>
					<span className='rating-form__progress-text'>{progressText}</span>
				</div>
			</header>
			<RatingFormBlock
				data={data}
				setData={setData}
				errors={errors}
				markers={blocks[step].markers}
				className='rating-form__block'
			/>
			<footer className='rating-form__footer'>
				{Boolean(step !== 0) && (
					<button
						type='button'
						disabled={processing}
						className='button rating-form__button'
						onClick={() => setStep(step - 1)}
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
				{step + 1 !== blocks.length ? (
					<button
						type='button'
						className='button button--accent rating-form__button rating-form__button--right'
						onClick={() => setStep(step + 1)}
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
				) : (
					<button
						type='submit'
						disabled={processing}
						className='button button--accent rating-form__button rating-form__button--right'
					>
						Завершить
					</button>
				)}
			</footer>
		</form>
	);
};

export default RatingForm;
