import React from 'react';
import { Head } from '@inertiajs/react';

import SubordinateList from '../../Components/Rating/SubordinateList.jsx';
import EmptyMessage from '../../Components/Shared/EmptyMessage.jsx';

const SubordinatesOverviewPage = ({ title = '', subordinates = [] }) => {
	return (
		<>
			<Head>
				<title>{title}</title>
			</Head>
			<h1 className='title page-content__title'>{title}</h1>
			{subordinates.length ? (
				<SubordinateList subordinates={subordinates} />
			) : (
				<EmptyMessage text='У вас нет подчиненных сотрудников' />
			)}
		</>
	);
};

export default SubordinatesOverviewPage;
