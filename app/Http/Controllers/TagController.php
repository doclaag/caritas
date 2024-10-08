<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::all();
        return response()->json($tags);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_etiqueta' => 'required|string|max:255',
            'descripcion_etiqueta' => 'required|string|max:255',
            'estado' => 'required|boolean',
            'usuarios_id' => 'required|exists:users,id',
        ]);

        $tag = Tag::create($request->all());
        return response()->json($tag, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'nombre_etiqueta' => 'sometimes|required|string|max:255',
            'descripcion_etiqueta' => 'sometimes|required|string|max:255',
            'estado' => 'sometimes|required|boolean',
            'usuarios_id' => 'sometimes|required|exists:users,id',
        ]);

        $tag->update($request->all());
        return response()->json($tag);
    }

    /**
     * Cambiar el estado de una etiqueta (Eliminar lógicamente).
     */
    public function cambiarEstado(Request $request, Tag $tag)
    {
        $request->validate([
            'estado' => 'required|boolean',
        ]);

        // Actualiza el estado de la etiqueta
        $tag->estado = $request->estado;
        $tag->save();

        return response()->json(['message' => 'Estado actualizado correctamente']);
    }

    /**
     * Remove the specified resource from storage.
     * En este caso no elimina, solo cambia el estado a 0 (soft delete).
     */
    public function destroy(Tag $tag)
    {
        $tag->update(['estado' => 0]);
        return response()->json(null, 204);
    }
}
