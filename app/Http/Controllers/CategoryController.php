<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryModel;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function getCategorias(Request $request)
{
    $categoriasPrincipales = CategoryModel::where('categoria_principal', 1)->get();
    $categoriasSecundarias = CategoryModel::where('categoria_principal', 0)->get();

    // Verificar si es una solicitud AJAX o de API y devolver JSON
    if ($request->wantsJson()) {
        return response()->json([
            'principales' => $categoriasPrincipales,
            'secundarias' => $categoriasSecundarias,
        ]);
    }

    // Respuesta Inertia por defecto
    return Inertia::render('Categories/Main', [
        'principales' => $categoriasPrincipales,
        'secundarias' => $categoriasSecundarias,
    ]);
}
}
