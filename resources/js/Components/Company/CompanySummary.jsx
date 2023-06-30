import React from 'react';
import clsx from 'clsx';

const CompanySummary = ({ data = [], className = '' }) => {
	if (!data.length) return false;

	return (
		<section className={clsx('company-summary', className)}>
			<h2 className='title title--small company-summary__title'>
				Cводка о компании
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
									{item.averageRating}
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
