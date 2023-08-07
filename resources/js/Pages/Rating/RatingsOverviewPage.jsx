import React from 'react';
import { Head } from '@inertiajs/react';

import RatingInstruction from '../../Components/Rating/RatingInstruction.jsx';
import RatingList from '../../Components/Rating/RatingList.jsx';

const RatingsOverviewPage = ({
	title = '',
	instruction = {},
	ratings = []
}) => {
	return (
		<>
			<Head>
				<title>{title}</title>
			</Head>
			<h1 className='visually-hidden'>{title}</h1>
			<div className='separator-container'>
				<RatingInstruction />
				<RatingList ratings={ratings} />
			</div>
		</>
	);
};

export default RatingsOverviewPage;
