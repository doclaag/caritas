<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FileCategory;

class FileCategoryController extends Controller
{
    public function store($archivoId, $categoriaId, $userId)
    {
        FileCategory::create([
            'archivo_id' => $archivoId,
            'categoria_id' => $categoriaId,
            'usuarios_id' => $userId,
        ]);
    }
}