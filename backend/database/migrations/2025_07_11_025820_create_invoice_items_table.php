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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_id')->unsigned()->nullable();
            $table->foreign('invoice_id')->on('users')->references('id')->onDelete('cascade');            
            $table->integer('client_id')->unsigned()->nullable();
            $table->foreign('client_id')->on('users')->references('id')->onDelete('cascade');
            $table->integer('servicio_id')->unsigned()->nullable();
            $table->foreign('servicio_id')->on('servicios')->references('id')->onDelete('cascade');
            $table->string('description');
            $table->decimal('price', 10, 2)->default(0)->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('invoice_items');
    }
};
