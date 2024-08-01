import { IAverageRating } from '@js/types/statistic/result.ts';

export interface ICompetencyResultCardProps {
	competence: string;
	description?: string;
	averageRatingByClient: IAverageRating;
	averageRating: number;
	averageRatingWithoutSelf: number;
	className?: string;
}
