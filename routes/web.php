<?php

use App\Http\Controllers\Company\SubordinateController;
use App\Http\Controllers\Rating\RatingController;
use App\Http\Controllers\Rating\ReportController;
use App\Http\Controllers\Shared\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')
    ->name('client.')
    ->group(function () {
        Route::get('/', HomeController::class)
            ->name('shared.home');

        Route::prefix('rating')
            ->controller(RatingController::class)
            ->name('rating.')
            ->group(function () {
                Route::get('/{rating}/{employee}', 'showForm')
                    ->name('rating.showForm')
                    ->can('view', 'rating');
                Route::post('/{ratingId}/{employeeId}', 'saveResult')
                    ->name('rating.saveResult');
            });

        Route::get('report', [ReportController::class, 'index'])
            ->name('rating.report.index');
        Route::get('report/export', [ReportController::class, 'export'])
            ->name('rating.report.export');

        Route::middleware('manager')
            ->prefix('subordinates')
            ->name('company.subordinates.')
            ->group(function () {
                Route::get('/', [SubordinateController::class, 'index'])->name('index');
                Route::get('/{employeeId}', [SubordinateController::class, 'show'])->name('show');
            });
    });
