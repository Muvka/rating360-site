import React from 'react';
import { Head } from '@inertiajs/react';

import RatingForm from '../../Components/Rating/RatingForm.jsx';

const RatingPage = ({
	title = '',
	ratingId = '0',
	employee = {},
	competences = []
}) => {
	const hasCompetences = Boolean(competences.length);

	return (
		<>
			<Head>
				<title>{title}</title>
			</Head>
			<h1 className='visually-hidden'>{title}</h1>
			{hasCompetences && (
				<RatingForm
					ratingId={ratingId}
					employee={employee}
					blocks={competences}
				/>
			)}
		</>
	);
};

export default RatingPage;
