<?php

/**
 * ---------------------------------------------------
 *  Desarrollado por: Jorge Méndez - Programandoweb
 *  Correo: lic.jorgemendez@gmail.com
 *  Celular: 3115000926
 *  Website: Programandoweb.net
 *  Proyecto: Ivoolve
 * ---------------------------------------------------
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_name')->nullable();
            
            $table->unsignedInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedInteger('employee_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');

            $table->decimal('total', 12, 2);
            $table->decimal('balance', 12, 2)->default(0);
            $table->enum('status', ['pendiente', 'en_progreso', 'completada', 'cancelada'])->default('pendiente');

            // 🔹 Nuevo campo para registrar el mes cancelado
            $table->string('month_paid', 20)->nullable()->comment('Mes y año al que corresponde el pago, ej: Octubre 2025');

            $table->timestamps();                        
        });
    }

    public function down(): void {
        Schema::dropIfExists('invoices');
    }
};
