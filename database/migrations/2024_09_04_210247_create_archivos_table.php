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
            $table->unsignedBigInteger('user_create');
            $table->boolean('estado');
            $table->boolean('publico');
            $table->timestamps();

            $table->index('nombre_archivo');
            $table->index('user_create');

            $table->foreign('user_create')->references('id')->on('users')->onDelete('no action')->onUpdate('no action');
        });
    }

    public function down()
    {
        Schema::dropIfExists('archivos');
    }
};
