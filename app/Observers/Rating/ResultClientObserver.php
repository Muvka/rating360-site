<?php

namespace App\Observers\Rating;

use App\Models\Rating\Rating;
use App\Models\Rating\Result;
use App\Models\Rating\ResultClient;

class ResultClientObserver
{
    public function created(ResultClient $resultClient): void
    {
        $rating = Rating::find($resultClient->result->rating_id);

        if (!$rating) {
            return;
        }

        $totalClients = 0;
        $finishedClients = 0;
        $matrixTemplates = $rating->matrix
            ->templates()
            ->with('clients')
            ->get();
        $results = Result::with('clients')
            ->where('rating_id', $rating->id)
            ->get();

        foreach ($matrixTemplates as $matrixTemplate) {
            $matrixClients = $matrixTemplate->clients
                ->pluck('company_employee_id');
            $resultClients = $results->firstWhere('company_employee_id', $matrixTemplate->company_employee_id)
                ?->clients
                ?->pluck('company_employee_id');

            $intersect = $matrixClients->intersect($resultClients);

            $totalClients += $matrixClients->count();
            $finishedClients += $intersect->count();
        }

        if ($totalClients === $finishedClients) {
            $rating->status = 'closed';
            $rating->save();
        }
    }
}
