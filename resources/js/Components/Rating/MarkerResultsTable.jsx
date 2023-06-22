import React from 'react';
import clsx from 'clsx';

const MarkerResultsTable = ({ markers = [], className = '' }) => {
	if (!markers.length) {
		return null;
	}

	return (
		<div className={clsx('table', className)}>
			<table className='table__table'>
				<thead>
					<tr>
						<td className='table__cell table__cell--head'>Маркер</td>
						<td className='table__cell table__cell--center table__cell--head'>
							Внешние клиенты
						</td>
						<td className='table__cell table__cell--center table__cell--head'>
							Внутренние клиенты
						</td>
						<td className='table__cell table__cell--center table__cell--head'>
							Руководитель
						</td>
						<td className='table__cell table__cell--center table__cell--head'>
							Самооценка
						</td>
					</tr>
				</thead>
				<tbody>
					{markers.map(marker => (
						<tr key={marker.id} className='table__row'>
							<td className='table__cell'>{marker.text}</td>
							<td className='table__cell table__cell--center'>
								{marker.ratings?.outer}
							</td>
							<td className='table__cell table__cell--center'>
								{marker.ratings?.inner}
							</td>
							<td className='table__cell table__cell--center'>
								{marker.ratings?.manager}
							</td>
							<td className='table__cell table__cell--center'>
								{marker.ratings?.self}
							</td>
						</tr>
					))}
				</tbody>
			</table>
		</div>
	);
};

export default MarkerResultsTable;
