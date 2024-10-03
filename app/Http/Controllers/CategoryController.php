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
    
        if ($request->wantsJson()) {
            return response()->json([
                'principales' => $categoriasPrincipales,
                'secundarias' => $categoriasSecundarias,
            ]);
        }
    
        return Inertia::render('Categories/Main', [
            'principales' => $categoriasPrincipales,
            'secundarias' => $categoriasSecundarias,
        ]);
    }
}
