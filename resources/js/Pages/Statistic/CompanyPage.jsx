import React from 'react';
import { Head } from '@inertiajs/react';

import SeparateWrapper from '../../Components/Shared/SeparateWrapper.jsx';
import StatisticFilter from '../../Components/Rating/StatisticFilter.jsx';
import Table from '../../Components/Shared/Table.jsx';

const GeneralStatisticPage = ({
	title = '',
	statistic = {},
	exportUrl = ''
}) => {
	const hasStatistic = Boolean(Object.keys(statistic).length);

	return (
		<>
			<Head>
				<title>{title}</title>
			</Head>
			<h1 className='title page-content__title'>{title}</h1>
			<SeparateWrapper>
				<StatisticFilter />
				{hasStatistic && (
					<>
						<Table columns={statistic.columns} data={statistic.data} />
						<a
							href={exportUrl}
							className='button button--accent'
							style={{
								marginTop: '16px'
							}}
						>
							Экспорт
						</a>
					</>
				)}
			</SeparateWrapper>
		</>
	);
};

export default GeneralStatisticPage;
