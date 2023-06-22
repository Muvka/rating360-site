import React from 'react';
import { Head } from '@inertiajs/react';
import RatingForm from '../../Components/Rating/RatingForm.jsx';

const RatingPage = ({ title = '', employeeName = '', competences = [] }) => {
	return (
		<>
			<Head>
				<title>{title}</title>
			</Head>
			<h1 className='visually-hidden'>{title}</h1>
			<RatingForm employeeName={employeeName} blocks={competences} />
		</>
	);
};

export default RatingPage;
