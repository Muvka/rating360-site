import React, { useId } from 'react';
import { useForm, usePage } from '@inertiajs/react';
import clsx from 'clsx';

import FormField from '../Shared/FormField.jsx';
import Select from '../Shared/Select.jsx';
import Checkbox from '../Shared/Checkbox.jsx';

const StatisticFilter = ({ className = '' }) => {
	const { formData = {}, filters = {} } = usePage().props;
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

	return (
		<section
			className={clsx('statistic-filter', className)}
			aria-labelledby={titleId}
		>
			<h2 id={titleId} className='visually-hidden'>
				Форма фильтрации
			</h2>
			<form className='statistic-filter__form' onSubmit={submitHandler}>
				{Boolean(formData.cities?.length) && (
					<FormField label='Город' idProp='inputId'>
						<Select
							value={formData.cities.find(city => city.value === data.city)}
							options={formData.cities}
							isClearable
							onChange={data => {
								setData('city', data ? data.value : '');
							}}
						/>
					</FormField>
				)}
				{Boolean(formData.companies?.length) && (
					<FormField label='Компания' idProp='inputId'>
						<Select
							value={formData.companies.find(
								company => company.value === data.company
							)}
							options={formData.companies}
							isClearable
							onChange={data => {
								setData('company', data ? data.value : '');
							}}
						/>
					</FormField>
				)}
				{Boolean(formData.divisions?.length) && (
					<FormField label='Отдел' idProp='inputId'>
						<Select
							value={formData.divisions.find(
								division => division.value === data.division
							)}
							options={formData.divisions}
							isClearable
							onChange={data => {
								setData('division', data ? data.value : '');
							}}
						/>
					</FormField>
				)}
				{Boolean(formData.subdivisions?.length) && (
					<FormField label='Подразделение' idProp='inputId'>
						<Select
							value={formData.subdivisions.find(
								subdivision => subdivision.value === data.subdivision
							)}
							options={formData.subdivisions}
							isClearable
							onChange={data => {
								setData('subdivision', data ? data.value : '');
							}}
						/>
					</FormField>
				)}
				{Boolean(formData.directions?.length) && (
					<FormField label='Направления' idProp='inputId'>
						<Select
							value={formData.directions.find(
								direction => direction.value === data.direction
							)}
							options={formData.directions}
							isClearable
							onChange={data => {
								setData('direction', data ? data.value : '');
							}}
						/>
					</FormField>
				)}
				{Boolean(formData.levels?.length) && (
					<FormField label='Уровень сотрудника' idProp='inputId'>
						<Select
							value={formData.levels.find(level => level.value === data.level)}
							options={formData.levels}
							isClearable
							onChange={data => {
								setData('level', data ? data.value : '');
							}}
						/>
					</FormField>
				)}
				{Boolean(formData.positions?.length) && (
					<FormField label='Должность' idProp='inputId'>
						<Select
							value={formData.positions.find(
								position => position.value === data.position
							)}
							options={formData.positions}
							isClearable
							onChange={data => {
								setData('position', data ? data.value : '');
							}}
						/>
					</FormField>
				)}
				{/* TODO: Исправить 'true' */}
				{Boolean(formData.self) && (
					<Checkbox
						label='Показать с учётом самооценки'
						checked={data.self}
						className='statistic-filter__field'
						onChange={event => {
							setData('self', event.target.checked);
						}}
					/>
				)}
				<button
					type='submit'
					disabled={processing}
					className='button button--accent button--small statistic-filter__field'
				>
					Применить
				</button>
			</form>
		</section>
	);
};

export default StatisticFilter;
