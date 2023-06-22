import React, { useMemo } from 'react';
import { Head } from '@inertiajs/react';

import CompanySummary from '../../Components/Company/CompanySummary.jsx';
import EmployeeFeedback from '../../Components/Company/EmployeeFeedback.jsx';
import DetailedRatingResults from '../../Components/Rating/DetailedRatingResults.jsx';
import RatingResultList from '../../Components/Rating/RatingResultList.jsx';
import RatingResultGrid from '../../Components/Rating/RatingResultGrid.jsx';
import downloadIconId from '../../../images/shared/icons/icon-download.svg';

const RatingReportPage = ({
	title = '',
	ratingResults = [],
	companySummary = [],
	employeeFeedback = {}
}) => {
	return (
		<>
			<Head>
				<title>{title}</title>
			</Head>
			<div className='page-content__header'>
				<h1 className='title title--light page-content__title'>{title}</h1>
				<button className='button button--small'>
					<svg
						width='24'
						height='24'
						className='button__icon'
						aria-hidden='true'
					>
						<use xlinkHref={`#${downloadIconId}`} />
					</svg>
					Скачать
				</button>
			</div>
			<RatingResultList results={ratingResults} />
			<div className='page-content__separator'></div>
			<RatingResultGrid results={ratingResults} />
			<div className='page-content__separator'></div>
			<CompanySummary data={companySummary} />
			<div className='page-content__separator'></div>
			<EmployeeFeedback
				positives={employeeFeedback.positives}
				negatives={employeeFeedback.negatives}
			/>
			<div className='page-content__separator'></div>
			<DetailedRatingResults data={ratingResults} />
		</>
	);
};

export default RatingReportPage;
