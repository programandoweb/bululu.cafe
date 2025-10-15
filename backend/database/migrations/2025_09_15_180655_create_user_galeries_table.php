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
        Schema::create('user_galeries', function (Blueprint $table) {
            $table->increments('id');

            // Relación con el usuario dueño de la galería
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Imagen almacenada
            $table->string('image');

            // Descripción opcional de la imagen
            $table->string('description')->nullable();

            // Estado: activa o eliminada (por defecto: activa)
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_galeries');
    }
};
