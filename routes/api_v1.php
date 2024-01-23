<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('api.auth')
    ->group(function () {
        Route::prefix('company/employees')
            ->controller(\App\Http\Controllers\Api\V1\Company\EmployeeController::class)
            ->group(function () {
                Route::get('managers', 'managers');
            });

        Route::prefix('statistic/corporate-values')
            ->controller(\App\Http\Controllers\Api\V1\Statistic\CorporateValueController::class)
            ->group(function () {
                Route::get('average', 'average');
            });
    });
