import React from 'react';
import { Head } from '@inertiajs/react';

import SeparateWrapper from '../../Components/Shared/SeparateWrapper.jsx';
import StatisticFilter from '../../Components/Statistic/StatisticFilter.jsx';
import Table from '../../Components/Shared/Table.jsx';
import StatisticResults from '../../Components/Statistic/StatisticResults.jsx';

const StatisticPage = ({ title = '' }) => {
	return (
		<>
			<Head>
				<title>{title}</title>
			</Head>
			<h1 className='title page-content__title'>{title}</h1>
			<SeparateWrapper>
				<StatisticFilter />
				<StatisticResults />
			</SeparateWrapper>
		</>
	);
};

export default StatisticPage;
