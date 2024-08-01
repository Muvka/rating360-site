import React, { useId, useMemo } from 'react';
import { usePage } from '@inertiajs/react';

import { CompetencyResultList } from '@js/Components/Statistic/competency-result-list';
import CompetencyResultGrid from './CompetencyResultGrid.jsx';
import EmployeeFeedback from './EmployeeFeedback.jsx';
import DetailedResults from './DetailedResults.jsx';
import CompanySummary from './CompanySummary.jsx';
import RatingComparison from './RatingComparison.jsx';
import Tabs from '../Shared/Tabs.jsx';

const EmployeeResults = () => {
	const { resultsByYear = [], ratingComparison = [] } = usePage().props;
	const titleId = useId();

	const tabs = useMemo(() => {
		const tabsResult = resultsByYear.map(result => ({
			id: result.id,
			title: result.title,
			content: (
				<>
					<CompetencyResultList results={result.competence} />
					<CompetencyResultGrid results={result.competence} />
					<EmployeeFeedback data={result.reviews} />
					<DetailedResults results={result.marker} />
					<CompanySummary data={result.company} />
				</>
			)
		}));

		tabsResult.push({
			id: 'comparison',
			title: 'Сравнение',
			content: <RatingComparison />
		});

		return tabsResult;
	}, [resultsByYear]);

	if (!tabs.length) return false;

	return (
		<div>
			<h2 id={titleId} className='visually-hidden'>
				Результаты сотрудника
			</h2>
			<Tabs
				tabs={tabs}
				titleId={titleId}
				tabPanelClassName='separator-container'
			/>
		</div>
	);
};

export default EmployeeResults;
