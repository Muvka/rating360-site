<?php

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
        Route::get('/', [\App\Http\Controllers\Rating\RatingController::class, 'index'])
            ->name('rating.ratings.index');

        Route::prefix('results')
            ->controller(\App\Http\Controllers\Statistic\ResultController::class)
            ->name('statistic.results.')
            ->group(function () {
                Route::get('/', 'index')
                    ->name('index');
                Route::get('create/{rating}/{employee}', 'create')
                    ->name('create');
                Route::get('{employee}', 'show')
                    ->name('show');
                Route::post('/{rating}/{employee}', 'store')
                    ->name('store');
            });

        Route::prefix('statistic')
            ->name('statistic.')
            ->middleware('admin')
            ->group(function () {
                Route::prefix('general')
                    ->controller(\App\Http\Controllers\Statistic\GeneralController::class)
                    ->name('general.')
                    ->group(function () {
                        Route::get('/', 'index')
                            ->name('index');
                        Route::get('export', 'export')
                            ->name('export');
                    });
                Route::prefix('company')
                    ->controller(\App\Http\Controllers\Statistic\CompanyController::class)
                    ->name('company.')
                    ->group(function () {
                        Route::get('/', 'index')
                            ->name('index');
                        Route::get('export', 'export')
                            ->name('export');
                    });
                Route::prefix('competence')
                    ->controller(\App\Http\Controllers\Statistic\CompetenceController::class)
                    ->name('competence.')
                    ->group(function () {
                        Route::get('/', 'index')
                            ->name('index');
                        Route::get('export', 'export')
                            ->name('export');
                    });
                Route::prefix('value')
                    ->controller(\App\Http\Controllers\Statistic\ValueController::class)
                    ->name('value.')
                    ->group(function () {
                        Route::get('/', 'index')
                            ->name('index');
                        Route::get('export', 'export')
                            ->name('export');
                    });
            });
    });

Route::get('login', [\App\Http\Controllers\User\AuthController::class, 'login'])
    ->name('client.user.auth.login');
