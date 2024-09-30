<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivosTable extends Migration
{
    public function up()
    {
        Schema::create('archivos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_archivo');
            $table->string('ubicacion_archivo');
            $table->boolean('estado')->default(1);
            $table->boolean('publico')->default(0);
            $table->foreignId('usuarios_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->index('nombre_archivo');
            $table->index('usuarios_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('archivos');
    }
};
