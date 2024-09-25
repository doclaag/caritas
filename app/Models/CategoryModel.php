<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    use HasFactory;

     // Define la tabla asociada al modelo si el nombre no sigue la convención de Laravel.
     protected $table = 'categorias'; // Asegúrate de que esta sea tu tabla real.

     // Las columnas que el modelo puede llenar (Eloquent utilizará estas para las operaciones en masa).
     protected $fillable = [
        'id',
        'nombre_categoria',
        'descripcion_categoria',
        'categoria_principal',
        'categoria_padre'
     ];
}
