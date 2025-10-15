<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Servicios\ServiciosController;
use App\Http\Controllers\V1\Products\ProductsController;
use App\Http\Controllers\V1\Products\CategoriesController;

// Ruta resource para el controlador de servicios
Route::resource('dashboard/products', ProductsController::class);
Route::post('dashboard/products/new', [ProductsController::class,"store"]);

Route::resource('dashboard/professional_profile', ServiciosController::class);
Route::post('dashboard/professional_profile/new', [ServiciosController::class,"store"]);

Route::resource('dashboard/services', ServiciosController::class);
Route::post('dashboard/services/new', [ServiciosController::class,"store"]);

Route::resource('dashboard/categories', CategoriesController::class);
Route::post('dashboard/categories/new', [CategoriesController::class,"store"]);
