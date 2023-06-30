import React from 'react';
import { Head } from '@inertiajs/react';

import SeparateWrapper from '../../Components/Shared/SeparateWrapper.jsx';
import RatingInstruction from '../../Components/Rating/RatingInstruction.jsx';
import RatingList from '../../Components/Rating/RatingList.jsx';

const HomePage = ({ title = '', instruction = {}, ratings = [] }) => {
	const hasInstruction = Boolean(instruction.text || instruction.video);
	const hasRatings = Boolean(ratings.length);

	return (
		<>
			<Head>
				<title>{title}</title>
			</Head>
			<h1 className='visually-hidden'>{title}</h1>
			<SeparateWrapper>
				{hasInstruction && <RatingInstruction />}
				{hasRatings && <RatingList ratings={ratings} />}
			</SeparateWrapper>
		</>
	);
};

export default HomePage;
