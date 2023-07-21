import React, { useId } from 'react';
import { usePage } from '@inertiajs/react';
import clsx from 'clsx';

import Table from '../Shared/Table.jsx';
import EmptyMessage from '../Shared/EmptyMessage.jsx';

const StatisticResults = ({ className = '' }) => {
	const { statistic = {}, exportUrl = '' } = usePage().props;
	const titleId = useId();

	if (!statistic.data || !statistic.data.length) {
		return (
			<EmptyMessage text='Сообщение, что либо нет результатов по фильтрам, либо фильтры не выбраны' />
		);
	}

	return (
		<section
			className={clsx('statistic-results', className)}
			aria-labelledby={titleId}
		>
			<h2 id={titleId} className='visually-hidden'>
				Статистика
			</h2>
			<Table
				columns={statistic.columns}
				data={statistic.data}
				className='statistic-results__table'
			/>
			<a
				href={exportUrl}
				className='button button--accent statistic-results__export'
			>
				Экспорт
			</a>
		</section>
	);
};

export default StatisticResults;
