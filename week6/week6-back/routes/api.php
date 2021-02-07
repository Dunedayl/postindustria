<?php

use App\Http\Controllers\ActorActionController;
use App\Http\Controllers\ActorController;
use App\Http\Controllers\ActorDataController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserActionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserCurrencyController;
use App\Http\Controllers\UsersDataController;
use App\Models\Actor;
use App\Models\ActorActions;
use Illuminate\Http\Request;
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


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::post('/check', [AuthController::class, 'index'])->middleware('auth:api');

Route::get('/data', [UserController::class, 'index'])->middleware('auth:api');

Route::prefix('/data')->group(
    function () {
        Route::post('/update', [UserController::class, 'update'])->middleware('auth:api');
    }
);

Route::get('/currencies', [UserCurrencyController::class, 'index'])->middleware('auth:api');

Route::prefix('/action')->group(
    function () {
        Route::post('/store', [UserActionController::class, 'store'])->middleware('auth:api');
        Route::post('/exchange', [UserActionController::class, 'exchange'])->middleware('auth:api');
        Route::post('/forceExchage', [UserActionController::class, 'forceExchage'])->middleware('auth:api');

    }
);


Route::get('/action/{from}/{to}', [UserActionController::class, 'makeReport'])->middleware('auth:api');
Route::get('/action/all', [UserActionController::class, 'getAllActions'])->middleware('auth:api');

Route::get('/rate/{date}/{currency}', [UserActionController::class, 'apiRate'])->middleware('auth:api');

