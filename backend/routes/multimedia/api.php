<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Multimedia\MultimediaController;

Route::middleware('auth:api')->group(function () {
    Route::delete('multimedia/{id}', [MultimediaController::class, 'deleteDS']);
    Route::post('multimedia/upload-rn', [MultimediaController::class, 'uploadRN']);
    Route::post('multimedia/upload-dashboard', [MultimediaController::class, 'uploadDS']);
    Route::resource('multimedia/upload', MultimediaController::class);      
    
});

//Route::post('multimedia/upload-open', [MultimediaController::class,"uploadOpen"]); 