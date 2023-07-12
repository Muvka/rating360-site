import React, { useEffect, useMemo, useState } from 'react';
import { useForm } from '@inertiajs/react';
import clsx from 'clsx';
import toast from 'react-hot-toast';

import RatingFormHeader from './RatingFormHeader.jsx';
import RatingFormBlock from './RatingFormBlock.jsx';
import RatingFormFooter from './RatingFormFooter.jsx';

const RatingForm = ({
	blocks = [],
	ratingId = '0',
	employee = {},
	className = ''
}) => {
	const localStorageRatingName = useMemo(() => {
		return `rating-${ratingId}-${employee.id}`;
	}, [ratingId, employee]);
	const flatMarkers = useMemo(() => {
		return blocks
			.map(block => {
				return block.markers;
			})
			.flat();
	}, [blocks]);
	const { data, setData, post, processing, errors } = useForm();
	const [step, setStep] = useState(0);
	const markers = Object.keys(errors).length
		? Object.keys(errors).reduce((acc, key) => {
				const markerId = key.replace('marker', '');
				const foundMarker = flatMarkers.find(
					marker => marker.id.toString() === markerId
				);

				if (foundMarker) {
					acc.push(foundMarker);
				}

				return acc;
		  }, [])
		: blocks[step].markers;

	const submitHandler = event => {
		event.preventDefault();

		post(route('client.rating.results.store', [ratingId, employee.id]), {
			onSuccess: () => {
				localStorage.removeItem(localStorageRatingName);
				toast.success('Результаты оценки успешно сохранены.');
			},
			onError: data => {
				toast.error('При отправке формы произошла ошибка!');
			}
		});
	};

	useEffect(() => {
		const { step: savedStep, data: savedData } = localStorage.getItem(
			localStorageRatingName
		)
			? JSON.parse(localStorage.getItem(localStorageRatingName))
			: { step: 0, data: {} };

		setData(savedData);
		setStep(blocks.length <= savedStep ? blocks.length - 1 : savedStep);
	}, []);

	useEffect(() => {
		localStorage.setItem(
			localStorageRatingName,
			JSON.stringify({
				step: step,
				data: data
			})
		);
	}, [data, step]);

	if (!blocks.length) {
		return false;
	}

	return (
		<form className={clsx('rating-form', className)} onSubmit={submitHandler}>
			<RatingFormHeader
				employee={employee.fullName}
				blocksNumber={blocks.length}
				step={step}
			/>
			<RatingFormBlock
				data={data}
				setData={setData}
				errors={errors}
				markers={markers}
				className='rating-form__block'
			/>
			<RatingFormFooter
				step={step}
				processing={processing}
				showBackButton={step !== 0 && !Object.keys(errors).length}
				showSubmitButton={step + 1 === blocks.length}
				changeStep={setStep}
				onSubmitClick={submitHandler}
			/>
		</form>
	);
};

export default RatingForm;
