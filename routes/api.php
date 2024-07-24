<?php

use App\Http\Controllers\UserTransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    /** User Routes */
    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {

        /** User Transaction Routes */
        Route::group(['prefix' => 'transactions', 'as' => 'transactions.'], function () {
            Route::get('', [UserTransactionController::class, 'index'])->name('index');
            Route::post('import', [UserTransactionController::class, 'import'])->name('import');
        });
    });
});
