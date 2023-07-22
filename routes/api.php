<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\SentConnectionController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::post('logout', [UserController::class, 'logout']);


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::apiResource('users', UserController::class);
    Route::post('withdraw-connection', [UserController::class, 'withdrawConnectionRequest']);
    Route::post('accept-connection', [UserController::class, 'acceptConnectionRequest']);
    Route::post('remove-connection', [UserController::class, 'removeConnectionRequest']);
    Route::get('sent-connections', [UserController::class, 'sentConnectionRequest']);
    Route::get('received-connections', [UserController::class, 'receivedConnectionRequest']);
    Route::get('connected-connections', [UserController::class, 'getConnectedConnections']);
    Route::get('get-count', [UserController::class, 'getCount']);
});