import React, { useId } from 'react';
import clsx from 'clsx';
import { usePage } from '@inertiajs/react';

import Table from '../Shared/Table.jsx';
import EmptyMessage from '../Shared/EmptyMessage.jsx';

const RatingComparison = ({ className = '' }) => {
	const { ratingComparison = {} } = usePage().props;
	const titleId = useId();

	return (
		<section
			className={clsx('rating-comparison', className)}
			aria-labelledby={titleId}
		>
			<h2 id={titleId} className='title title--small rating-comparison__title'>
				Сравнение результатов оценки
			</h2>
			{ratingComparison?.columns && ratingComparison?.data ? (
				<Table
					columns={ratingComparison.columns}
					data={ratingComparison.data}
				/>
			) : (
				<EmptyMessage
					text='У вас нет оценок за этот и (или) предыдущий год.'
					withButton={false}
				/>
			)}
		</section>
	);
};

export default RatingComparison;
