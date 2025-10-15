<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Coupons\CouponsController; // Asegúrate de importar el controlador adecuadamente
use App\Http\Controllers\V1\Users\UsersController; // Asegúrate de importar el controlador adecuadamente
use App\Http\Controllers\V1\Settings\SettingsController;


Route::get('/emails/email_register_business', [SettingsController::class, 'get_email_register_business']);
Route::get('/dashboard/coupons/new', [CouponsController::class, 'new_generate']);
Route::get('/coupons/{id}', [CouponsController::class, 'openShow']);
Route::post('/coupons/{id}/use', [CouponsController::class, 'use_coupons']);
Route::post('/coupons/search', [CouponsController::class, 'coupons_search']);

Route::middleware('auth:api')->group(function () {
    Route::get('open/memberships', [UsersController::class, 'memberships']);
    Route::get('/dashboard/coupons/{id}', [CouponsController::class, 'show']);
    Route::post('/dashboard/coupons/new', [CouponsController::class, 'store']);
    Route::put('/dashboard/coupons/{id}', [CouponsController::class, 'update']);    
});

