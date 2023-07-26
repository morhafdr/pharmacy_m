<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DebtRecordController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InvoiceController;
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
 Route::get('p_outstock',[ProductController::class,'outstock']);
Route::post('password/forget',[AuthController::class, 'ForgetPassword']);
Route::post('password/reset',[AuthController::class, 'ResetPassword']);
Route::post('login',[AuthController::class, 'Login']);
Route::post('User/register',[AuthController::class, 'UserRegister']);
Route::post('Admin/register',[AuthController::class, 'AdminRegister']);
Route::group( ['middleware' => [ 'auth:api' , 'access']],
   function()
{
    Route::delete('Delete-suppliers/{supplier}',[SupplierController::class,'destroy'])->name('delete-supplier');});


    Route::group( ['middleware' => ['auth:api']],function(){

        Route::post('add-a-debt',[DebtRecordController::class,'store']);

    Route::get('logout',[AuthController::class, 'Logout']);
    Route::get('Get-Gategorie',[CategoryController::class,'index']);
    Route::post('categories',[CategoryController::class,'store']);
    Route::post('categories/{category}/update',[CategoryController::class,'update']);
    Route::delete('categories/{category}/delete',[CategoryController::class,'destroy']);

/*
    Route::get('products',[ProductController::class,'index']);
    Route::get('products/create',[ProductController::class,'create']);
    Route::get('expired-products',[ProductController::class,'expired']);*/
    Route::get('products/{id}',[ProductController::class,'show']);

    Route::get('p_expired',[ProductController::class,'expired']);
    Route::post('products/create',[ProductController::class,'store']);
    Route::post('products/{product}',[ProductController::class,'update']);
    Route::post('search',[ProductController::class,'search']);




    Route::post('store',[InvoiceController::class,'store']);
    Route::post('DaySales',[InvoiceController::class,'DaySales']);
    Route::get('TodaySales',[InvoiceController::class,'TodaySales']);
    Route::get('Profet_T',[InvoiceController::class,'Profet_T']);
    Route::get('Best-Selling',[InvoiceController::class,'BestSelling']);

    Route::get('suppliers',[SupplierController::class,'index']);
    Route::post('add-supplier',[SupplierController::class,'store']);
    Route::get('suppliers/{supplier}',[SupplierController::class,'show']);


    Route::get('employees',[EmployeeController::class,'index']);
    Route::post('add-employee',[EmployeeController::class,'store']);
    Route::get('Get-Employee/{id}',[EmployeeController::class,'show']);
    Route::get('Delet-Employee/{id}',[EmployeeController::class,'destroy']);


    Route::post('Update-supplier/{supplier}',[SupplierController::class,'update']);

    Route::get('purchases',[PurchaseController::class,'index']);
    Route::post('add-purchase',[PurchaseController::class,'store']);
    Route::get('purchases/{id}',[PurchaseController::class,'show']);
    Route::post('update-purchases/{id}',[PurchaseController::class,'update']);
    Route::delete('delete-purchases/{id}',[PurchaseController::class,'destroy']);

//customer
    Route::get('customers',[CustomerController::class,'index']);
    Route::post('add-customer',[CustomerController::class,'store']);
    Route::get('customers/{customer}',[CustomerController::class,'show']);
    Route::post('customers/{customer}/update',[CustomerController::class,'update']);
    Route::post('customers/{customer}/delete',[CustomerController::class,'destroy']);
//company
    Route::get('companies',[CompanyController::class,'index']);
    Route::post('add-company',[CompanyController::class,'store']);
    Route::get('companies/{company}',[CompanyController::class,'show']);
    Route::post('companies/{company}/update',[CompanyController::class,'update']);
    Route::post('companies/{company}/delete',[CompanyController::class,'destroy']);


   });
