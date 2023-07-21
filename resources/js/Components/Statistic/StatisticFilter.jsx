import React, { useId } from 'react';
import { useForm, usePage } from '@inertiajs/react';
import clsx from 'clsx';

import FormField from '../Shared/FormField.jsx';
import Select from '../Shared/Select.jsx';
import Checkbox from '../Shared/Checkbox.jsx';

const StatisticFilter = ({ className = '' }) => {
	const { fields = [], filters = {} } = usePage().props;
	const { data, setData, get, transform, processing } = useForm(filters);
	const titleId = useId();

	transform(data => {
		const result = {};

		for (let key in data) {
			if (data[key]) {
				result[key] = data[key];
			}
		}

		return result;
	});

	const submitHandler = event => {
		event.preventDefault();

		get(route(route().current()), {
			only: ['statistic', 'exportUrl'],
			preserveState: true,
			preserveScroll: true
		});
	};

	if (!fields) return false;

	return (
		<section
			className={clsx('statistic-filter', className)}
			aria-labelledby={titleId}
		>
			<h2 id={titleId} className='visually-hidden'>
				Форма фильтрации
			</h2>
			<form className='statistic-filter__form' onSubmit={submitHandler}>
				{fields.map(field => {
					if (field.type === 'multiselect') {
						return (
							<FormField key={field.name} label={field.label} idProp='inputId'>
								<Select
									value={
										data[field.name] &&
										field.data.filter(item =>
											data[field.name].includes(item.value)
										)
									}
									options={field.data}
									isClearable
									isMulti
									onChange={newValues => {
										setData(
											field.name,
											newValues.length
												? newValues.map(newValue => newValue.value)
												: []
										);
									}}
								/>
							</FormField>
						);
					} else if (field.type === 'select') {
						return (
							<FormField key={field.name} label={field.label} idProp='inputId'>
								<Select
									value={field.data.find(
										item => item.value === data[field.name]
									)}
									options={field.data}
									isClearable
									onChange={newValue => {
										setData(field.name, newValue ? newValue.value : '');
									}}
								/>
							</FormField>
						);
					} else if (field.type === 'checkbox') {
						return (
							/* TODO: Исправить 'true' */
							<Checkbox
								key={field.name}
								label='Показать с учётом самооценки'
								checked={data[field.name]}
								className='statistic-filter__field statistic-filter__field--center'
								onChange={event => {
									setData(field.name, event.target.checked);
								}}
							/>
						);
					}
				})}
				<button
					type='submit'
					disabled={processing}
					className='button button--accent button--small statistic-filter__submit'
				>
					Применить
				</button>
			</form>
		</section>
	);
};

export default StatisticFilter;
