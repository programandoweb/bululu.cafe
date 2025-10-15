<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Invoice\InvoiceController; // Asegúrate de importar el controlador adecuadamente

/*
Route::post('/dashboard/invoices/{id}/pay-full', [InvoiceController::class, 'pay_full']);
Route::post('/dashboard/invoices/{id}/pay-partial', [InvoiceController::class, 'pay_partial']);
Route::post('/dashboard/invoices/{id}/generate-invoice', [InvoiceController::class, 'generate_invoice']);
Route::resource('dashboard/event_invoice', InvoiceController::class);
*/

//Route::resource('dashboard/invoice_payments', InvoiceController::class);
Route::post('/dashboard/invoices/invoice_payments', [InvoiceController::class, 'generate_invoice']);
