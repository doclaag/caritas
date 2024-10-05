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
        Schema::create('archivos_etiquetas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('archivo_id');
            $table->unsignedBigInteger('etiqueta_id');

            $table->index('archivo_id');
            $table->index('etiqueta_id');

            $table->foreign('archivo_id')->references('id')->on('archivos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('etiqueta_id')->references('id')->on('etiquetas')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archivos_etiquetas');
    }
};