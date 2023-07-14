import React from 'react';
import { Head } from '@inertiajs/react';

import SeparateWrapper from '../../Components/Shared/SeparateWrapper.jsx';
import StatisticFilter from '../../Components/Rating/StatisticFilter.jsx';
import GeneralStatisticsTable from '../../Components/Rating/GeneralStatisticsTable.jsx';

const GeneralStatisticPage = ({ title = '', formData = {} }) => {
	return (
		<>
			<Head>
				<title>{title}</title>
			</Head>
			<h1 className='title page-content__title'>{title}</h1>
			<SeparateWrapper>
				{Boolean(Object.keys(formData).length) && <StatisticFilter />}
				<GeneralStatisticsTable />
			</SeparateWrapper>
		</>
	);
};

export default GeneralStatisticPage;
