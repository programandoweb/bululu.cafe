<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Comments\CommentsController;

// Rutas RESTful
Route::resource('dashboard/comments', CommentsController::class)->names([
    'index'   => 'comments.index',
    'store'   => 'comments.store',
    'show'    => 'comments.show',
    'update'  => 'comments.update',
    'destroy' => 'comments.destroy',
]);

// Si necesitas una ruta personalizada adicional, la agregas aparte
// Route::post('dashboard/comments/new', [CommentsController::class, 'store']);
