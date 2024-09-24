<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryModel;

class CategoryController extends Controller
{
    // Función para obtener las categorías principales usando Eloquent
    public function getCategoriasPrincipales()
    {
        // Utilizamos Eloquent para obtener todas las categorías donde `categoria_principal` es 1
        $categoriasPrincipales = CategoryModel::where('categoria_principal', 1)->get();

        // Retornamos los resultados como JSON para AJAX
        return response()->json($categoriasPrincipales);
    }

    // Función para obtener las categorías secundarias usando Eloquent
    public function getCategorias()
    {
        // Utilizamos Eloquent para obtener todas las categorías donde `categoria_principal` es 0
        $categorias = CategoryModel::where('categoria_principal', 0)->get();

        // Retornamos los resultados como JSON para AJAX
        return response()->json($categorias);
    }
}
