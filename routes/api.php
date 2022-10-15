<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CoadebitController;
use App\Http\Controllers\API\CoakreditController;
use App\Http\Controllers\API\FrontofficeController;
use App\Http\Controllers\API\HitungController;
use App\Http\Controllers\API\JurnalumumController;
use App\Http\Controllers\API\KasbankController;
use App\Http\Controllers\API\KasbesarController;
use App\Http\Controllers\API\KaskecilController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::name('auth.')->group(function(){
    Route::post('login', [UserController::class, 'login'])->name('login');
    Route::post('register', [UserController::class, 'register'])->name('register');

    Route::middleware('auth:sanctum')->group(function(){
        Route::post('logout', [UserController::class, 'logout'])->name('logout');
        Route::get('user', [UserController::class,'fetch'])->name('fetch');
    });

});

//coadebit API
Route::prefix('coadebit')->middleware('auth:sanctum')->name('coadebit.')->group(function(){
    Route::get('', [CoadebitController::class,'fetch'])->name('fetch');
    Route::post('', [CoadebitController::class,'create'])->name('create');
    Route::post('update/{id}', [CoadebitController::class,'update'])->name('update');
    Route::delete('{id}', [CoadebitController::class,'destroy'])->name('delete');
});

//coakredit API
Route::prefix('coakredit')->middleware('auth:sanctum')->name('coakredit.')->group(function(){
    Route::get('', [CoakreditController::class,'fetch'])->name('fetch');
    Route::post('', [CoakreditController::class,'create'])->name('create');
    Route::post('update/{id}', [CoakreditController::class,'update'])->name('update');
    Route::delete('{id}', [CoakreditController::class,'destroy'])->name('delete');
});

//frontoffice API
Route::prefix('frontoffice')->middleware('auth:sanctum')->name('frontoffice.')->group(function(){
    Route::get('', [FrontofficeController::class,'fetch'])->name('fetch');
    Route::post('', [FrontofficeController::class,'create'])->name('create');
    Route::post('update/{id}', [FrontofficeController::class,'update'])->name('update');
    Route::delete('{id}', [FrontofficeController::class,'destroy'])->name('delete');
});

//kasbank API
Route::prefix('kasbank')->middleware('auth:sanctum')->name('kasbank.')->group(function(){
    Route::get('', [KasbankController::class,'fetch'])->name('fetch');
    Route::post('', [KasbankController::class,'create'])->name('create');
    Route::post('update/{id}', [KasbankController::class,'update'])->name('update');
    Route::delete('{id}', [KasbankController::class,'destroy'])->name('delete');
});

//kasbesar API
Route::prefix('kasbesar')->middleware('auth:sanctum')->name('kasbesar.')->group(function(){
    Route::get('', [KasbesarController::class,'fetch'])->name('fetch');
    Route::post('', [KasbesarController::class,'create'])->name('create');
    Route::post('update/{id}', [KasbesarController::class,'update'])->name('update');
    Route::delete('{id}', [KasbesarController::class,'destroy'])->name('delete');
});

//kaskecil API
Route::prefix('kaskecil')->middleware('auth:sanctum')->name('kaskecil.')->group(function(){
    Route::get('', [KaskecilController::class,'fetch'])->name('fetch');
    Route::post('', [KaskecilController::class,'create'])->name('create');
    Route::post('update/{id}', [KaskecilController::class,'update'])->name('update');
    Route::delete('{id}', [KaskecilController::class,'destroy'])->name('delete');
});

//jurnalumum API
Route::prefix('jurnalumum')->middleware('auth:sanctum')->name('jurnalumum.')->group(function(){
    Route::get('', [JurnalumumController::class,'fetch'])->name('fetch');
    Route::post('', [JurnalumumController::class,'create'])->name('create');
    Route::post('update/{id}', [JurnalumumController::class,'update'])->name('update');
    Route::delete('{id}', [JurnalumumController::class,'destroy'])->name('delete');
});

//hitung API
Route::prefix('hitung')->middleware('auth:sanctum')->name('hitung.')->group(function(){
    Route::get('fo', [HitungController::class,'fo'])->name('fo');
    Route::get('bank', [HitungController::class,'kasbank'])->name('bank');
    Route::post('update/{id}', [HitungController::class,'update'])->name('update');
    Route::delete('{id}', [HitungController::class,'destroy'])->name('delete');
});

 
