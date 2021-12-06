<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\MessageContoller;

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
Route::post('login', [UsersController::class, 'login']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('send-message', [MessageContoller::class, 'sendMessage']);
    Route::post('get-message', [MessageContoller::class, 'getMessage']);
    Route::post('reply-message', [MessageContoller::class, 'replyMessage']);
    Route::post('get-all-message', [MessageContoller::class, 'getAllMessage']);
});