<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Memberships\MembershipsController;
use App\Http\Controllers\V1\Memberships\DashboardMembershipsController;
Route::resource('memberships', MembershipsController::class);
Route::post('memberships/new', [MembershipsController::class,"store"]);


Route::get('dashboard/memberships', [MembershipsController::class,"get"]);
Route::put('dashboard/memberships/{id}', [MembershipsController::class,"update"]);


Route::get('dashboard/users_memberships_by_status', [DashboardMembershipsController::class,"index"]);
Route::get('dashboard/users_memberships_payments', [DashboardMembershipsController::class,"paids"]);




