import React, { useState } from 'react';
import { Head } from '@inertiajs/react';
import html2pdf from 'html2pdf.js';

import SeparateWrapper from '../../Components/Shared/SeparateWrapper.jsx';
import CompanySummary from '../../Components/Statistic/CompanySummary.jsx';
import EmployeeFeedback from '../../Components/Statistic/EmployeeFeedback.jsx';
import DetailedResults from '../../Components/Statistic/DetailedResults.jsx';
import CompetencyResultList from '../../Components/Statistic/CompetencyResultList.jsx';
import CompetencyResultGrid from '../../Components/Statistic/CompetencyResultGrid.jsx';
import downloadIconId from '../../../images/shared/icons/icon-download.svg';
import PageContentHeader from '../../Components/Shared/PageContentHeader.jsx';
import BadgeText from '../../Components/Shared/BadgeText.jsx';
import RatingComparison from '../../Components/Statistic/RatingComparison.jsx';

const ResultDetailsPage = ({
	title = '',
	companySummary = [],
	employeeFeedback = {},
	competenceRatingResults = [],
	markerRatingResults = [],
	ratingComparison = [],
	progressText = ''
}) => {
	const [disabled, setDisabled] = useState(false);
	const hasCompetenceRatingResults = Boolean(competenceRatingResults.length);
	const hasSummary = Boolean(companySummary.length);
	const hasFeedback = Boolean(Object.keys(employeeFeedback).length);
	const hasMarkerRatingResults = Boolean(
		Object.keys(markerRatingResults).length
	);

	const pageHeaderTrailingComponent = () => {
		return (
			hasCompetenceRatingResults && (
				<button
					type='button'
					disabled={disabled}
					className='button button--small'
					onClick={exportToPdf}
				>
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
			)
		);
	};

	const exportToPdf = async () => {
		window.print();
		// setDisabled(true);
		// const contentElement = document.querySelector('.page-content');
		//
		// if (contentElement) {
		// 	const options = {
		// 		margin: [6, 0],
		// 		html2canvas: { scale: 4 }
		// 	};
		// 	await html2pdf().set(options).from(contentElement).save(`${title}.pdf`);
		// 	setDisabled(false);
		// }
	};

	return (
		<>
			<Head>
				<title>{title}</title>
			</Head>
			<PageContentHeader
				title={title}
				content={<BadgeText text={progressText} />}
				trailing={pageHeaderTrailingComponent()}
				className='page-content__header'
			/>
			<SeparateWrapper>
				{hasCompetenceRatingResults && (
					<CompetencyResultList results={competenceRatingResults} />
				)}
				{hasCompetenceRatingResults && (
					<CompetencyResultGrid results={competenceRatingResults} />
				)}
				{hasFeedback && <EmployeeFeedback data={employeeFeedback} />}
				{hasMarkerRatingResults && (
					<DetailedResults results={markerRatingResults} />
				)}
				{hasSummary && <CompanySummary data={companySummary} />}
				<RatingComparison />
			</SeparateWrapper>
		</>
	);
};

export default ResultDetailsPage;
