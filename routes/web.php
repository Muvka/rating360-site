<?php

use App\Http\Controllers\Rating\RatingController;
use App\Http\Controllers\Rating\ResultController;
use App\Http\Controllers\User\AuthController;
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
        Route::get('/', [RatingController::class, 'index'])
            ->name('rating.ratings.index');

        Route::prefix('results')
            ->controller(ResultController::class)
            ->name('rating.results.')
            ->group(function () {
                Route::get('/', 'index')
                    ->name('index');
                Route::get('create/{rating}/{employee}', 'create')
                    ->name('create')
                    ->can('create', [\App\Models\Rating\Result::class, 'rating', 'employee']);
                Route::get('{employee}', 'show')
                    ->name('show');
                Route::post('/{rating}/{employee}', 'store')
                    ->name('store')
                    ->can('create', [\App\Models\Rating\Result::class, 'rating', 'employee']);
                Route::get('export/{employee}', 'export')
                    ->name('export');
            });
    });

Route::get('login', [AuthController::class, 'login'])
    ->name('client.user.auth.login');
