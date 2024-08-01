export interface IAverageRating {
	self: number;
	manager: number;
	inner: number;
	outer: number;
}

export interface ICompetenceResult {
	name: string;
	description?: string;
	averageRating: number;
	averageRatingWithoutSelf: number;
}
