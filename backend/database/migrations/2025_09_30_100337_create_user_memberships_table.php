<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_memberships', function (Blueprint $table) {
            $table->increments('id');
             // Relación con el usuario dueño de la galería
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('membership_id')->unsigned();
            $table->foreign('membership_id')->references('id')->on('memberships')->onDelete('cascade');

             // Fechas de vigencia de la membresía
            $table->dateTime('start_date')->nullable()->comment('Fecha de inicio de la membresía');
            $table->dateTime('end_date')->nullable()->comment('Fecha de finalización de la membresía');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_memberships');
    }
};
