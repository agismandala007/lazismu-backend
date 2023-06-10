<?php

use App\Http\Controllers\API\CabangController;
use App\Http\Controllers\API\CoaController;
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
use App\Models\Frontoffice;
use App\Models\Kaskecil;

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
        Route::post('user/update/{id}', [UserController::class,'update'])->name('update');
        Route::get('userall', [UserController::class,'fetchAll'])->name('fetchall');
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
    Route::post('/import_excel', [CoakreditController::class,'import_excel'])->name('importcoakredit');
});

//coa API
Route::prefix('coa')->middleware('auth:sanctum')->name('coa.')->group(function(){
    Route::get('', [CoaController::class,'fetch'])->name('fetch');
    Route::post('', [CoaController::class,'create'])->name('create');
    Route::post('update/{id}', [CoaController::class,'update'])->name('update');
    Route::delete('{id}', [CoaController::class,'destroy'])->name('delete');
    Route::post('/import_excel', [CoaController::class,'importcoa'])->name('importcoa');
    Route::get('/export', [CoaController::class,'export'])->name('exportcoa');

});

//frontoffice API
Route::prefix('frontoffice')->middleware('auth:sanctum')->name('frontoffice.')->group(function(){
    Route::get('', [FrontofficeController::class,'fetch'])->name('fetch');
    Route::post('', [FrontofficeController::class,'create'])->name('create');
    Route::post('update/{id}', [FrontofficeController::class,'update'])->name('update');
    Route::delete('{id}', [FrontofficeController::class,'destroy'])->name('delete');
    Route::get('/export', [FrontofficeController::class,'export'])->name('exportfo');

});

//kasbank API
Route::prefix('kasbank')->middleware('auth:sanctum')->name('kasbank.')->group(function(){
    Route::get('', [KasbankController::class,'fetch'])->name('fetch');
    Route::post('', [KasbankController::class,'create'])->name('create');
    Route::post('update/{id}', [KasbankController::class,'update'])->name('update');
    Route::delete('{id}', [KasbankController::class,'destroy'])->name('delete');
    Route::get('/export', [KasbankController::class,'export'])->name('exportkasbank');
});

//kasbesar API
Route::prefix('kasbesar')->middleware('auth:sanctum')->name('kasbesar.')->group(function(){
    Route::get('', [KasbesarController::class,'fetch'])->name('fetch');
    Route::post('', [KasbesarController::class,'create'])->name('create');
    Route::post('update/{id}', [KasbesarController::class,'update'])->name('update');
    Route::delete('{id}', [KasbesarController::class,'destroy'])->name('delete');
    //export excel
});
Route::get('/kasbesar/export', [KasbesarController::class,'export'])->middleware('auth:sanctum')->name('export');

//kaskecil API
Route::prefix('kaskecil')->middleware('auth:sanctum')->name('kaskecil.')->group(function(){
    Route::get('', [KaskecilController::class,'fetch'])->name('fetch');
    Route::post('', [KaskecilController::class,'create'])->name('create');
    Route::post('update/{id}', [KaskecilController::class,'update'])->name('update');
    Route::delete('{id}', [KaskecilController::class,'destroy'])->name('delete');
    Route::get('export', [KaskecilController::class,'export'])->name('export');
});

//jurnalumum API
Route::prefix('jurnalumum')->middleware('auth:sanctum')->name('jurnalumum.')->group(function(){
    Route::get('', [JurnalumumController::class,'fetch'])->name('fetch');
    Route::post('', [JurnalumumController::class,'create'])->name('create');
    Route::post('update/{id}', [JurnalumumController::class,'update'])->name('update');
    Route::delete('{id}', [JurnalumumController::class,'destroy'])->name('delete');
    Route::get('/export', [JurnalumumController::class,'export'])->name('exportju');

});

//hitung API
Route::prefix('hitung')->middleware('auth:sanctum')->name('hitung.')->group(function(){
    Route::get('statistik', [HitungController::class,'statisAll'])->name('statistik');
    Route::get('fo', [HitungController::class,'fo'])->name('fo');
    Route::get('coa', [HitungController::class,'coa'])->name('coa');
    Route::get('kasbesar', [HitungController::class,'kasbesar'])->name('hkasbesar');
    Route::get('jurnalumum', [HitungController::class,'jurnalumum'])->name('hju');
    Route::get('kaskecil', [HitungController::class,'kaskecil'])->name('hkaskecil');
    Route::get('kasbank', [HitungController::class,'kasbank'])->name('hkasbank');
    Route::post('update/{id}', [HitungController::class,'update'])->name('update');
    Route::delete('{id}', [HitungController::class,'destroy'])->name('delete');
});
//cabang API
Route::prefix('cabang')->middleware('auth:sanctum')->name('cabang.')->group(function(){
    Route::get('', [CabangController::class,'fetch'])->name('fetch');
    Route::post('', [CabangController::class,'create'])->name('create');
    Route::post('update/{id}', [CabangController::class,'update'])->name('update');
    Route::delete('{id}', [CabangController::class,'destroy'])->name('delete');
});

 
