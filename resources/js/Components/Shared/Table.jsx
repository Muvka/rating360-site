import React, { useMemo } from 'react';
import clsx from 'clsx';

const Table = ({
	columns = [],
	data = [],
	placeholder = '',
	className = ''
}) => {
	const renderHeader = useMemo(() => {
		return (
			<tr>
				{columns.map((column, index) => (
					<td key={index} className='table__cell table__cell--head'>
						{column.label}
					</td>
				))}
			</tr>
		);
	}, [columns]);

	const renderRows = useMemo(() => {
		return data.map((row, rowIndex) => (
			<tr key={`row-${rowIndex}`} className='table__row'>
				{columns.map((column, columnIndex) => (
					<td key={`column-${rowIndex}-${columnIndex}`} className='table__cell'>
						{row[column.key] ? (
							row[column.key]['href'] ? (
								<a
									href={row[column.key]['href']}
									target='_blank'
									className='table__link'
								>
									{row[column.key]['text']}
								</a>
							) : (
								row[column.key]
							)
						) : (
							placeholder
						)}
					</td>
				))}
			</tr>
		));
	}, [data]);

	return (
		<div className={clsx('table', 'scrollbar', className)}>
			<table className='table__table'>
				<thead>{renderHeader}</thead>
				<tbody>{renderRows}</tbody>
			</table>
		</div>
	);
};

export default Table;
