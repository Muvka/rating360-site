import React from 'react';
import { Head } from '@inertiajs/react';
import StatisticFilter from '../../Components/Rating/StatisticFilter.jsx';

const GeneralStatisticPage = ({ title = '', formData = {} }) => {
	return (
		<>
			<Head>
				<title>{title}</title>
			</Head>
			<h1 className='title page-content__title'>{title}</h1>
			{Boolean(Object.keys(formData).length) && (
				<StatisticFilter formData={formData} />
			)}
		</>
	);
};

export default GeneralStatisticPage;
