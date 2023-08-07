import React, { useId } from 'react';
import clsx from 'clsx';

const CompanySummary = ({ data = [], className = '' }) => {
	const titleId = useId();

	if (!data.length) return false;

	return (
		<section
			className={clsx('company-summary', className)}
			aria-labelledby={titleId}
		>
			<h2 id={titleId} className='title title--small company-summary__title'>
				Сводные данные по компании
			</h2>
			{Boolean(data.length) ? (
				<table className='company-summary__table'>
					<thead>
						<tr>
							<th className='text text--bold company-summary__cell'>
								Компетенция
							</th>
							<th className='text text--bold text--right company-summary__cell'>
								Средняя оценка в компании
							</th>
						</tr>
					</thead>
					<tbody>
						{data.map(item => (
							<tr key={item.competence} className='company-summary__row'>
								<td className='text company-summary__cell'>
									{item.competence}
								</td>
								<td className='text text--right company-summary__cell'>
									{item.rating}
								</td>
							</tr>
						))}
					</tbody>
				</table>
			) : null}
		</section>
	);
};

export default CompanySummary;
