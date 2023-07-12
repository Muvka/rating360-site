import React, { useState } from 'react';
import { Head } from '@inertiajs/react';
import html2pdf from 'html2pdf.js';

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
	const [disabled, setDisabled] = useState(false);
	const hasResults = Boolean(shortResults.length);
	const hasSummary = Boolean(companySummary.length);
	const hasFeedback = Boolean(Object.keys(employeeFeedback).length);
	const hasDetailed = Boolean(detailedResults.length);

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
			<div className='page-content__header'>
				<h1 className='title title--light page-content__title'>{title}</h1>
				{hasResults && Boolean(exportRoute) && (
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
