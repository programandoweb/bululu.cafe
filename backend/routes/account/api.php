<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Users\UserAccountController; // Asegúrate de importar el controlador adecuadamente
use App\Http\Controllers\V1\Users\EmployeesController;
use App\Http\Controllers\V1\Users\UsersController;
use App\Http\Controllers\V1\Users\UsersDashboardController;


Route::get('/dashboard/account', [UserAccountController::class, 'account_show']);

Route::get('/dashboard/account/basic', [UserAccountController::class, 'show']);
Route::post('/dashboard/account/basic', [UserAccountController::class, 'update']);
Route::put('/dashboard/account/basic', [UserAccountController::class, 'update']);

Route::get('/dashboard/my-business', [UserAccountController::class, 'business']);
Route::post('/dashboard/my-business', [UserAccountController::class, 'update_business']);

Route::resource('dashboard/employees', EmployeesController::class);
Route::post('dashboard/employees/new', [EmployeesController::class,"store"]);

Route::post('users/update-image', [UsersController::class,"updateImage"]);
Route::post('users/gallery/upload', [UsersController::class,"usersGalleryUpload"]);
Route::delete('users/gallery/delete/{id}', [UsersController::class,"usersGalleryDelete"]); // 🔥 Nueva ruta

Route::get('users/gallery', [UsersController::class,"usersGallery"]); // 🔥 Nueva ruta

Route::get('/business/init', [UsersController::class, 'init']);
Route::get('users/init', [UsersController::class, 'user_init']);
Route::put('users/update', [UsersController::class, 'user_update']);

Route::post('dashboard/business/update', [UsersController::class, 'business_update']);


Route::get('dashboard/business/profile', [UserAccountController::class, 'business']);

Route::get('dashboard/business/show', [UsersDashboardController::class, 'business']);
Route::get('dashboard/requests', [UsersController::class, 'requests']);





