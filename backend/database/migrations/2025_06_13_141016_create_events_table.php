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
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');

            // Relación con el cliente/proveedor que crea el evento o promoción
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            // Tipo de registro: evento o promoción
            $table->enum('type', ['event', 'promotion'])->default('event');

            // Campos según payload
            $table->string('nombre');                    // título del evento
            $table->string('duracion')->nullable();      // rango de fechas/horas como texto
            $table->dateTime('fecha_evento')->nullable(); // nueva fecha/hora exacta del evento
            $table->longText('descripcion')->nullable(); // descripción completa
            $table->string('portada')->nullable();       // portada (URL/path)
            
            // Multimedia y categorías en JSON
            $table->json('galeria')->nullable();         // galería de imágenes
            $table->json('artistas')->nullable();        // artistas relacionados
            $table->json('categories')->nullable();      // categorías (ej: Bar, Electro)

            // Control
            $table->enum('publication_status', ['draft', 'published', 'cancelled'])
                  ->default('published');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
