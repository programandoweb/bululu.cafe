<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Users\UsersDashboardController;
use App\Http\Controllers\V1\Memberships\MembershipsController;

Route::resource('/dashboard/users', UsersDashboardController::class);
Route::post('/dashboard/users/new', [UsersDashboardController::class,"store"]);


Route::resource('/dashboard/companies', UsersDashboardController::class);

Route::get('/dashboard/companies/{id}/membership', [UsersDashboardController::class,"get_membership"]);
Route::post('/dashboard/companies/{id}/membership', [UsersDashboardController::class,"set_membership"]);


Route::post('/dashboard/companies/new_by_IA', [UsersDashboardController::class,"store_by_ia"]);

Route::resource('dashboard/users_memberships', MembershipsController::class)->names([
    'index'   => 'memberships.index',
    'store'   => 'memberships.store',
    'show'    => 'memberships.show',
    'update'  => 'memberships.update',
    'destroy' => 'memberships.destroy',
]);

