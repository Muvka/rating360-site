import React from 'react';
import clsx from 'clsx';

import FormField from '../Shared/FormField.jsx';
import RadioGroup from '../Shared/RadioGroup.jsx';

const answers = [
	{
		value: 'always',
		label: 'Всегда'
	},
	{
		value: 'often',
		label: 'Часто'
	},
	{
		value: 'sometimes',
		label: 'Иногда'
	},
	{
		value: 'rarely',
		label: 'Редко'
	},
	{
		value: 'never',
		label: 'Никогда'
	},
	{
		value: 'no_information',
		label: 'Нет информации'
	}
];

const RatingFormBlock = ({
	data = {},
	setData = () => {},
	errors = {},
	markers = [],
	className = ''
}) => {
	if (!markers.length) {
		return null;
	}

	return (
		<div className={clsx('rating-form-block', className)}>
			{markers.map(marker => (
				<div key={marker.id} className='rating-form-block__group'>
					<p className='text rating-form-block__marker'>{marker.text}</p>
					<FormField
						label={
							marker.answer_type === 'default'
								? 'Выберите ответ'
								: 'Введите ответ'
						}
						hiddenLabel
						fieldset
						className={clsx({
							'rating-form-block--text-input': marker.answer_type === 'text'
						})}
						error={errors[`marker${marker.id}`]}
					>
						{marker.answer_type === 'default' ? (
							<RadioGroup
								name={`marker-${marker.id}`}
								value={data[`marker${marker.id}`]}
								options={answers}
								onChange={event =>
									setData(`marker${marker.id}`, event.target.value)
								}
							/>
						) : (
							<textarea
								rows={5}
								value={data[`marker${marker.id}`]}
								placeholder='Ваш ответ'
								className={clsx('text-input', {
									'text-input--invalid': errors[`marker${marker.id}`]
								})}
								onChange={event =>
									setData(`marker${marker.id}`, event.target.value)
								}
							/>
						)}
					</FormField>
				</div>
			))}
		</div>
	);
};

export default RatingFormBlock;
