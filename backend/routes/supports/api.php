<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Comments\CommentsController;


Route::get('/dashboard/supports', [CommentsController::class, 'summary']);
Route::get('/dashboard/supports/{id}/children', [CommentsController::class, 'summary_childrens']);