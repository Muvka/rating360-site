import React from 'react';
import { Head } from '@inertiajs/react';

import downloadIconId from '../../../images/shared/icons/icon-download.svg';
import ContentHeader from '../../Components/Shared/ContentHeader.jsx';
import {
	Button,
	ButtonSize,
	ButtonVariant
} from '@js/Components/Shared/buttons/button/';
import BadgeText from '../../Components/Shared/BadgeText.jsx';
import EmployeeResults from '../../Components/Statistic/EmployeeResults.jsx';

const ResultDetailsPage = ({ title = '', progressText = '' }) => {
	const pageHeaderTrailingComponent = () => {
		return (
			<Button
				type='button'
				variant={ButtonVariant.Secondary}
				size={ButtonSize.Small}
				onClick={exportToPdf}
			>
				<svg width='24' height='24' className='button__icon' aria-hidden='true'>
					<use xlinkHref={`#${downloadIconId}`} />
				</svg>
				Скачать
			</Button>
		);
	};

	const exportToPdf = async () => {
		window.print();
	};

	return (
		<>
			<Head>
				<title>{title}</title>
			</Head>
			<ContentHeader
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
