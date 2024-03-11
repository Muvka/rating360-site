<?php

namespace App\Http\Controllers\Api\V1\Statistic;

use App\Http\Controllers\Controller;
use App\Services\CorporateValueSelectionService;
use Exception;
use Illuminate\Http\Request;

class CorporateValueController extends Controller
{
    public function average(Request $request, CorporateValueSelectionService $corporateValueSelectionService)
    {
        try {
            $values = $corporateValueSelectionService->getLastYearAverageRating($request->get('filter'));

            return response()->json([
                'data' => $values,
            ]);
        } catch (Exception $exception) {
            logger()->error('Ошибка при получении данных средней оценки по корпоративным ценностям: '.$exception->getMessage());

            return response()->json([
                'message' => 'Ошибка при получении данных средней оценки по корпоративным ценностям.',
            ], 500);
        }
    }
}
