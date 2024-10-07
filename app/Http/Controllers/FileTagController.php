<?php

namespace App\Http\Controllers;

use App\Models\FileTag;
use Illuminate\Http\Request;

class FileTagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $archivosEtiquetas = FileTag::all();
        return response()->json($archivosEtiquetas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'archivo_id' => 'required|exists:files,id',
            'etiqueta_id' => 'required|exists:etiquetas,id',
        ]);

        $archivoEtiqueta = FileTag::create($request->all());
        return response()->json($archivoEtiqueta, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(FileTag $archivoEtiqueta)
    {
        return response()->json($archivoEtiqueta);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FileTag $archivoEtiqueta)
    {
        $request->validate([
            'archivo_id' => 'sometimes|required|exists:files,id',
            'etiqueta_id' => 'sometimes|required|exists:etiquetas,id',
        ]);

        $archivoEtiqueta->update($request->all());
        return response()->json($archivoEtiqueta);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FileTag $archivoEtiqueta)
    {
        $archivoEtiqueta->delete();
        return response()->json(null, 204);
    }
}
