import React from 'react';
import { Head } from '@inertiajs/react';

import StatisticFilter from '../../Components/Statistic/StatisticFilter.jsx';
import StatisticResults from '../../Components/Statistic/StatisticResults.jsx';

const StatisticPage = ({ title = '' }) => {
	return (
		<>
			<Head>
				<title>{title}</title>
			</Head>
			<h1 className='title page-content__title'>{title}</h1>
			<div className='separator-container'>
				<StatisticFilter />
				<StatisticResults />
			</div>
		</>
	);
};

export default StatisticPage;
