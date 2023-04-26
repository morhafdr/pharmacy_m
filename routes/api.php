<?php

use App\Http\Controllers\AuthController;
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
Route::post('password/forget',[AuthController::class, 'ForgetPassword']);
Route::post('password/reset',[AuthController::class, 'ResetPassword']);
Route::post('login',[AuthController::class, 'Login']);
Route::post('register',[AuthController::class, 'Register']);
Route::group( ['middleware' => ['auth:api']],function(){
    Route::get('logout',[AuthController::class, 'Logout']);
   });
   
