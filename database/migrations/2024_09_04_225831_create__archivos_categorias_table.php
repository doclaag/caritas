<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivosCategoriasTable extends Migration
{
    public function up()
    {
        Schema::create('archivos_categorias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('archivo_id');
            $table->unsignedBigInteger('categoria_id');
            $table->unsignedBigInteger('user_created');
            $table->timestamps();

            $table->index('archivo_id');
            $table->index('categoria_id');
            $table->index('user_created');

            $table->foreign('archivo_id')->references('id')->on('archivos');
            $table->foreign('categoria_id')->references('id')->on('categorias');
            $table->foreign('user_created')->references('id')->on('users')->onDelete('no action')->onUpdate('no action');
        });
    }

    public function down()
    {
        Schema::dropIfExists('archivos_categorias');
    }
};
