<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Settings\SettingsController;

Route::get('dashboard/emails/{type}', [SettingsController::class,'show']);
Route::post('dashboard/emails/{type}', [SettingsController::class,"store"]);
Route::put('dashboard/emails/{type}', [SettingsController::class,"update"]);


Route::get('dashboard/tags', [SettingsController::class,"get_tags"]);
Route::post('dashboard/tags', [SettingsController::class,"store_tags"]);
Route::put('dashboard/tags/{id}', [SettingsController::class,"uddate_tags"]);
Route::delete('dashboard/tags/{id}', [SettingsController::class,"delete_tags"]);
Route::get('dashboard/master_tables/by-label', [SettingsController::class, 'get_group_by_label']);
