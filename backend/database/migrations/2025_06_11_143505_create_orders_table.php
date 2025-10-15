<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
            
            // Relación con el negocio o servicio adquirido
            $table->integer('business_id')->unsigned()->nullable();
            $table->foreign('business_id')->on('businesses')->references('id')->onDelete('cascade');
            
            // Estado de la orden
            $table->enum('status', ['pendiente', 'procesando', 'completada', 'cancelada'])->default('pendiente');

            // Fecha programada del servicio (si aplica)
            $table->dateTime('scheduled_at')->nullable();

            // Precio final de la orden
            $table->decimal('total_price', 10, 2)->nullable();

            // Método de pago (opcional)
            $table->string('payment_method')->nullable();

            // Información de contacto o entrega adicional (opcional)
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
