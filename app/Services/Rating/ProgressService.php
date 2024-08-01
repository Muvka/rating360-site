<?php

namespace App\Services\Rating;

use App\Models\Rating\MatrixTemplateClient;
use App\Models\Rating\Rating;
use App\Models\Statistic\Client;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ProgressService
{
    public function getProgress(Rating $rating): int
    {
        if ($rating->status === 'closed') {
            return 100;
        }

        $totalClients = MatrixTemplateClient::select(DB::raw('count(*) as count'))
            ->whereHas('template.matrix.ratings', function (Builder $query) use ($rating) {
                $query->where('id', $rating->id);
            })
            ->whereHas('employee', function (Builder $query) {
                $query->whereNull('deleted_at');
            })
            ->get()
            ->pluck('count')
            ->first();

        $finishedClients = Client::select(DB::raw('count(*) as count'))
            ->whereHas('result.rating', function (Builder $query) use ($rating) {
                $query->where('id', $rating->id);
            })
            ->whereHas('employee', function (Builder $query) {
                $query->whereNull('deleted_at');
            })
            ->get()
            ->pluck('count')
            ->first();

        return (int) $totalClients === 0 ? $totalClients : round(($finishedClients / $totalClients) * 100);
    }
}
