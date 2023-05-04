<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
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
    Route::get('categories',[CategoryController::class,'index']);
    Route::post('categories',[CategoryController::class,'store']);
    Route::post('categories/{category}/update',[CategoryController::class,'update']);
    Route::delete('categories/{category}/delete',[CategoryController::class,'destroy']);
/*
    Route::get('products',[ProductController::class,'index']);
    Route::get('products/create',[ProductController::class,'create']);
    Route::get('expired-products',[ProductController::class,'expired']);
    Route::get('products/{product}',[ProductController::class,'show']);
    Route::get('outstock-products',[ProductController::class,'outstock']);
    Route::post('products/create',[ProductController::class,'store']);
    Route::post('products/{product}',[ProductController::class,'update']);*/
    Route::post('search',[ProductController::class,'search']);

    Route::get('suppliers',[SupplierController::class,'index']);
    Route::post('add-supplier',[SupplierController::class,'store']);
    Route::get('suppliers/{supplier}',[SupplierController::class,'show']);
    Route::delete('suppliers/',[SupplierController::class,'destroy']);
    Route::put('suppliers/{supplier}}',[SupplierController::class,'update']);

   
    Route::get('purchases',[PurchaseController::class,'index']);
    Route::post('add-purchase',[PurchaseController::class,'store']);
    Route::get('purchases/{purchase}',[PurchaseController::class,'show']);
    Route::post('purchases/{purchase}/update',[PurchaseController::class,'update']);
    Route::delete('purchases{purchase}/delete',[PurchaseController::class,'destroy']);
   });
   
