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

Route::get('employee/autocomplete', [\App\Http\Controllers\Company\EmployeeController::class, 'autocomplete'])
    ->name('client.company.employees.autocomplete');

Route::name('client.user.')
    ->group(function () {
        Route::middleware('guest')
            ->controller(\App\Http\Controllers\User\LoginController::class)
            ->name('login.')
            ->group(function () {
                Route::get('login', 'show')
                    ->name('show');
                Route::post('login', 'login')
                    ->name('login');
                Route::get('moodle-login', 'moodleLogin')
                    ->name('moodle_login');
            });

        Route::middleware('auth')
            ->get('logout', [\App\Http\Controllers\User\LogoutController::class, 'logout'])
            ->name('logout.logout');
    });
