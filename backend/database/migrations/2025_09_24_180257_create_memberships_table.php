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
        Schema::create('memberships', function (Blueprint $table) {
            $table->increments('id');

            $table->string('label', 150);           // Nombre de la membresía
            $table->decimal('price', 10, 2);        // Precio de la membresía
            $table->string('icon')->nullable();     // Icono opcional
            $table->json('options')->nullable();    // Colores, borderColor, etc.
            $table->boolean('is_current')->default(false);

            // 🔹 Campos cuantificables
            $table->unsignedInteger('photos_limit')->default(0);       // fotos permitidas
            $table->unsignedInteger('events_per_month')->default(0);   // eventos por mes
            $table->unsignedInteger('promotions_limit')->default(0);   // promociones
            $table->unsignedInteger('push_notifications')->default(0); // notificaciones push

            // 🔹 Campos booleanos de características
            $table->boolean('profile_included')->default(false);        // Perfil del establecimiento
            $table->boolean('branding_support')->default(false);        // Branding y asesoría
            $table->boolean('map_visibility')->default(false);          // Visibilidad en mapa/búsqueda
            $table->boolean('priority_support')->default(false);        // Soporte preferencial
            $table->boolean('enhanced_positioning')->default(false);    // Posicionamiento mejorado

            // 🔹 Extras flexibles
            $table->json('features')->nullable(); // características adicionales opcionales

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
