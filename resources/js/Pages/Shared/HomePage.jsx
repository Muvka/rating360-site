import React from 'react';
import { Head } from '@inertiajs/react';

import RatingInstruction from '../../Components/Rating/RatingInstruction.jsx';
import RatingList from '../../Components/Rating/RatingList.jsx';

const HomePage = ({ title = '', ratings = [] }) => {
	return (
		<>
			<Head>
				<title>{title}</title>
			</Head>
			<h1 className='visually-hidden'>{title}</h1>
			<RatingInstruction />
			<div className='page-content__separator' />
			<RatingList ratings={ratings} />
		</>
	);
};

export default HomePage;
