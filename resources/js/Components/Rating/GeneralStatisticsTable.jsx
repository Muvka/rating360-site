import React, { useId } from 'react';
import clsx from 'clsx';
import { usePage } from '@inertiajs/react';

const GeneralStatisticsTable = ({ className = '' }) => {
	const { results } = usePage().props;
	const titleId = useId();

	console.log(results);

	return (
		<section
			className={clsx('statistic-table', className)}
			aria-labelledby={titleId}
		>
			<h2 id={titleId} className='visually-hidden'>
				Таблица статистики
			</h2>
			<div className={clsx('table', 'scrollbar', className)}>
				<table className='table__table'>
					<thead>
						<tr>
							<td className='table__cell table__cell--center table__cell--head'>
								Сотрудник
							</td>
							<td className='table__cell table__cell--center table__cell--head'>
								Должность
							</td>
							<td className='table__cell table__cell--center table__cell--head'>
								Город
							</td>
							<td className='table__cell table__cell--center table__cell--head'>
								Уровень сотрудника
							</td>
							<td className='table__cell table__cell--center table__cell--head'>
								Отдел
							</td>
							<td className='table__cell table__cell--center table__cell--head'>
								Оценка внешние клиенты
							</td>
							<td className='table__cell table__cell--center table__cell--head'>
								Оценка внутренние клиенты
							</td>
							<td className='table__cell table__cell--center table__cell--head'>
								Оценка руководитель
							</td>
							<td className='table__cell table__cell--center table__cell--head'>
								Средняя оценка
							</td>
							<td className='table__cell table__cell--center table__cell--head'>
								Средняя оценка без самооценки
							</td>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</section>
	);
};

export default GeneralStatisticsTable;
