<?php

use App\Http\Controllers\BelcorpApiController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\PromptController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/obtener-token', [BelcorpApiController::class, 'obtenerToken']);

Route::get('/get-products', [GeneralController::class, 'get_products']);

// TODO: Como recibir multipart
