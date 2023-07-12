import React from 'react';
import { Head } from '@inertiajs/react';

import StatisticFilter from '../../Components/Rating/StatisticFilter.jsx';

const GeneralStatisticPage = ({ title = '' }) => {
	return (
		<>
			<Head>
				<title>{title}</title>
			</Head>
			<h1 className='title page-content__title'>{title}</h1>
			<StatisticFilter />
		</>
	);
};

export default GeneralStatisticPage;
