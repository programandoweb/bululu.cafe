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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_group_id')->nullable();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('image')->nullable();
            $table->string('cover')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->enum('user_type', ['natural', 'juridica'])->default('natural');
            $table->string('identification_number')->unique()->nullable();
            $table->enum('identification_type', [
                'cedula_ciudadania',
                'cedula_extrajeria',
                'nit',
                'pasaporte',
                'otro'
            ])->nullable();
            $table->string('whatsapp_link')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('tax_no')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable()->default("CO");

            // 🔹 Campos de negocio
            $table->string('description', 500)->nullable();
            $table->json('schedule')->nullable();
            $table->json('gallery')->nullable();
            $table->json('categories')->nullable();
            $table->json('eventsToday')->nullable();
            $table->json('promotions')->nullable();

            // 🔹 Día de pago mensual
            $table->unsignedTinyInteger('payment_day')->nullable()->comment('Día del mes en que el cliente debe realizar el pago (1-31)');

            // 🔹 Estado del usuario
            $table->enum('status', ['activo', 'inactivo', 'solicitud', 'rechazado'])->default('solicitud');

            // 🔹 Tracking del origen de registro
            $table->string('marketing_source')->nullable()->comment('Origen detectado: facebook, instagram, google, whatsapp, direct, etc.');
            $table->timestamp('first_touch_at')->nullable()->comment('Primera interacción detectada con el sitio');
            $table->json('marketing_data')->nullable()->comment('Datos completos del objeto de marketing (utm, fbclid, gclid, etc.)');

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
