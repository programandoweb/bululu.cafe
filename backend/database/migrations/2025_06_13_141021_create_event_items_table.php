<?php

/**
 * ---------------------------------------------------
 *  Desarrollado por: Jorge Méndez - Programandoweb
 *  Correo: lic.jorgemendez@gmail.com
 *  Celular: 3115000926
 *  website: Programandoweb.net
 *  Proyecto: Ivoolve
 * ---------------------------------------------------
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_items', function (Blueprint $table) {
            $table->increments('id');

            // Relación con el evento
            $table->integer('event_id')->unsigned();
            $table->foreign('event_id')->on('events')->references('id')->onDelete('cascade');

            // Relación con el cliente que crea el evento
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Cantidad estimada requerida (ej: 10 mesas, 40 sillas)
            $table->integer('quantity')->default(1);

            // Observaciones específicas para este item (opcional)
            $table->text('notes')->nullable();

            // Estado del item: pendiente (editable) o aceptado (bloqueado)
            $table->enum('status', ['pendiente', 'aceptado'])->default('pendiente');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_items');
    }
};
