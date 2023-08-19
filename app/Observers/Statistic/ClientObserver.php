<?php

namespace App\Observers\Statistic;

use App\Models\Rating\Rating;
use App\Models\Statistic\Client;
use App\Models\Statistic\Result;

class ClientObserver
{
    public function created(Client $client): void
    {
        $rating = Rating::find($client->result->rating_id);

        if (! $rating) {
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
            $clients = $results->firstWhere('company_employee_id', $matrixTemplate->company_employee_id)
                ?->clients
                ?->pluck('company_employee_id');

            $intersect = $matrixClients->intersect($clients);

            $totalClients += $matrixClients->count();
            $finishedClients += $intersect->count();
        }

        if ($totalClients === $finishedClients) {
            $rating->status = 'closed';
            $rating->save();
        }
    }
}
