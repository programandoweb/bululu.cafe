<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable()->unsigned();
            $table->foreign('parent_id')->references('id')->on('comments')->onDelete('cascade');
            $table->longText('mensaje');
            $table->enum('type', ['Pagos', 'Comentario', 'Soporte', 'Reporte de Usuario'])->default('Comentario');
            $table->enum('status', ['pending', 'reviewed', 'resolved'])->default('pending');
            $table->string('image')->nullable();
            $table->longText('module')->default("")->nullable();
            $table->longText('pathname')->default("")->nullable();
            $table->longText('json')->default("")->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
