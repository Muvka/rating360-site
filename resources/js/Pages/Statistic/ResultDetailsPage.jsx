import React from 'react';
import { Head } from '@inertiajs/react';
import html2pdf from 'html2pdf.js';

import downloadIconId from '../../../images/shared/icons/icon-download.svg';
import PageContentHeader from '../../Components/Shared/PageContentHeader.jsx';
import BadgeText from '../../Components/Shared/BadgeText.jsx';
import EmployeeResults from '../../Components/Statistic/EmployeeResults.jsx';

const ResultDetailsPage = ({ title = '', progressText = '' }) => {
	const pageHeaderTrailingComponent = () => {
		return (
			<button
				type='button'
				className='button button--small'
				onClick={exportToPdf}
			>
				<svg width='24' height='24' className='button__icon' aria-hidden='true'>
					<use xlinkHref={`#${downloadIconId}`} />
				</svg>
				Скачать
			</button>
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
			<EmployeeResults />
		</>
	);
};

export default ResultDetailsPage;
