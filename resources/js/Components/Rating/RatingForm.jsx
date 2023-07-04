import React, { useMemo, useState } from 'react';
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
	const flatMarkers = useMemo(() => {
		return blocks
			.map(block => {
				return block.markers;
			})
			.flat();
	}, [blocks]);
	const initialFormData = useMemo(() => {
		return flatMarkers.reduce((acc, marker) => {
			acc[`marker${marker.id}`] = '';
			return acc;
		}, {});
	}, [flatMarkers]);
	const { data, setData, post, processing, errors } = useForm(initialFormData);
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
				toast.success('Результаты оценки успешно сохранены.');
			},
			onError: data => {
				toast.error('При отправке формы произошла ошибка!');
			}
		});
	};

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
