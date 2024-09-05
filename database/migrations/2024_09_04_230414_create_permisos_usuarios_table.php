<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermisosUsuariosTable extends Migration
{
    public function up()
    {
        Schema::create('permisos_usuarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vista_id')->constrained('vistas')->onDelete('no action')->onUpdate('no action');
            $table->foreignId('permiso_id')->constrained('permisos')->onDelete('no action')->onUpdate('no action');
            $table->foreignId('rol_id')->constrained('roles')->onUpdate('cascade')->onDelete('no action');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('permisos_usuarios');
    }
};
