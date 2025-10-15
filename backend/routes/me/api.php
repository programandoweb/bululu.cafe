<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Users\UsersController;
Route::get('/users/profile', [UsersController::class, 'user_profile']);