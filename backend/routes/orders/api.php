<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Orders\OrdersController; // Asegúrate de importar el controlador adecuadamente
use App\Http\Controllers\V1\Events\EventOrdersController; // Asegúrate de importar el controlador adecuadamente

Route::get('/dashboard/orders', [OrdersController::class, 'index']);

Route::resource('dashboard/event_orders', EventOrdersController::class);


Route::put('/dashboard/event_orders/order/{id}/status', [EventOrdersController::class, 'status']);
Route::put('/dashboard/event_orders/order/{id}/generate-invoice', [EventOrdersController::class, 'generate_invoice']);
