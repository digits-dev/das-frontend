<?php

use App\Http\Controllers\LookupController;
use App\Http\Controllers\WarrantyRequestController;
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
    return view('index');
});
Route::post('/tracking', [WarrantyRequestController::class,'show'])->name('checkWarranty');
Route::post('/get-stores', [LookupController::class,'getStores'])->name('getStores');
Route::post('/get-backend-stores', [LookupController::class,'getBackendStores'])->name('getBackendStores');
Route::post('/store-drop-off', [LookupController::class,'getStoreDropOff'])->name('getStoreDropOff');
Route::post('/branch-drop-off', [LookupController::class,'getBranchDropOff'])->name('getBranchDropOff');
Route::post('/get-city', [LookupController::class,'getCity'])->name('getCity');
Route::post('/refund-mode', [LookupController::class,'getRefundMode'])->name('getRefundMode');

Route::get('/create-warranty', [WarrantyRequestController::class,'create'])->name('createWarranty');
Route::post('/create-warranty', [WarrantyRequestController::class,'store'])->name('saveWarrantyRequest');


