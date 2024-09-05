<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogErrorTable extends Migration
{
    public function up()
    {
        Schema::create('log_error', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('vista_id');
            $table->string('descripcion');
            $table->timestamps();

            $table->index('usuario_id');
            $table->index('vista_id');

            $table->foreign('usuario_id')->references('id')->on('users');
            $table->foreign('vista_id')->references('id')->on('vistas');
        });
    }


    public function down()
    {
        Schema::dropIfExists('log_error');
    }
};
