<?php

use App\Http\Controllers\GeneralController;
use App\Http\Controllers\PromptController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/get-products-json', [GeneralController::class, 'getProductsJson']);

Route::post('/prompts/store', [PromptController::class, 'store']);
Route::post('/prompts/send_prompt', [PromptController::class, 'send_prompt']);
Route::post('/prompts/retry_prompt', [PromptController::class, 'retry_prompt']);
