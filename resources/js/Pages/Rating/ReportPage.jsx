import React from 'react';
import { Head } from '@inertiajs/react';

import SeparateWrapper from '../../Components/Shared/SeparateWrapper.jsx';
import CompanySummary from '../../Components/Company/CompanySummary.jsx';
import EmployeeFeedback from '../../Components/Company/EmployeeFeedback.jsx';
import DetailedRatingResults from '../../Components/Rating/DetailedRatingResults.jsx';
import RatingResultList from '../../Components/Rating/RatingResultList.jsx';
import RatingResultGrid from '../../Components/Rating/RatingResultGrid.jsx';
import downloadIconId from '../../../images/shared/icons/icon-download.svg';

const ReportPage = ({
	title = '',
	exportRoute = '',
	companySummary = [],
	employeeFeedback = {},
	shortResults = [],
	detailedResults = []
}) => {
	const hasResults = Boolean(shortResults.length);
	const hasSummary = Boolean(companySummary.length);
	const hasFeedback = Boolean(Object.keys(employeeFeedback).length);
	const hasDetailed = Boolean(detailedResults.length);

	return (
		<>
			<Head>
				<title>{title}</title>
			</Head>
			<div className='page-content__header'>
				<h1 className='title title--light page-content__title'>{title}</h1>
				{hasResults && Boolean(exportRoute) && (
					<a href={exportRoute} className='button button--small'>
						<svg
							width='24'
							height='24'
							className='button__icon'
							aria-hidden='true'
						>
							<use xlinkHref={`#${downloadIconId}`} />
						</svg>
						Скачать
					</a>
				)}
			</div>
			<SeparateWrapper>
				{hasResults && <RatingResultList results={shortResults} />}
				{hasResults && <RatingResultGrid results={shortResults} />}
				{hasFeedback && <EmployeeFeedback data={employeeFeedback} />}
				{hasDetailed && <DetailedRatingResults results={detailedResults} />}
				{hasSummary && <CompanySummary data={companySummary} />}
			</SeparateWrapper>
		</>
	);
};

export default ReportPage;
