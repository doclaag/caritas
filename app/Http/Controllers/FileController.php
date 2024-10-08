<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\File;
use App\Models\CategoryModel;
use App\Models\FileTag;
use Inertia\Inertia;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        $categoriaPrincipalId = $request->input('categoria');
        $categoriaPrincipal = CategoryModel::find($categoriaPrincipalId);
        if (!$categoriaPrincipal) {
            return response()->json(['error' => 'Categoría no encontrada'], 400);
        }
        $categoriaPrincipalNombre = $categoriaPrincipal->nombre_categoria;
        $destinationPath = 'public/CARITASPRUEBASDOCS/' . $categoriaPrincipalNombre;

        // Verificar si la carpeta existe, si no, crearla
        if (!Storage::exists($destinationPath)) {
            Storage::makeDirectory($destinationPath);
        }

        // Verificar si se seleccionó una subcategoría
        $subcategoriaId = $request->input('subcategoria');
        if ($subcategoriaId) {
            $subcategoria = CategoryModel::find($subcategoriaId);
            if ($subcategoria && $subcategoria->categoria_principal == 0) {
                $subcategoriaNombre = $subcategoria->nombre_categoria;
                $destinationPath .= '/' . $subcategoriaNombre;

                // Verificar si la subcarpeta existe, si no, crearla
                if (!Storage::exists($destinationPath)) {
                    Storage::makeDirectory($destinationPath);
                }
            }
        }

        $file = $request->file('file');
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $cleanName = $this->validarNombre($originalName);
        $fileName = $cleanName . '.' . $extension;
        $filePath = $destinationPath . '/' . $fileName;

        // Verificar si el archivo ya existe
        if (Storage::exists($filePath)) {
            if ($request->input('action') === 'replace') {
                Storage::delete($filePath);
            } elseif ($request->input('action') === 'rename') {
                $fileName = $cleanName . '_' . time() . '.' . $extension;
                $filePath = $destinationPath . '/' . $fileName;
            } else {
                return response()->json(['message' => 'El archivo ya existe', 'action' => 'exists'], 409);
            }
        }

        // Guardar el archivo en la carpeta correspondiente
        $file->storeAs($destinationPath, $fileName);

        $fileUrl = Storage::url($filePath);

        // Verificar si el usuario está autenticado
        if (Auth::check()) {
            $userId = Auth::id();
        } else {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        // Crear el registro del archivo
        $archivo = File::create([
            'nombre_archivo' => $fileName,
            'ubicacion_archivo' => $fileUrl,
            'estado' => $request->input('estado', 0),
            'publico' => $request->input('publico', 0),
            'usuarios_id' => $userId,
        ]);

        // Crear la relación en la tabla archivos_etiquetas
        FileTag::create([
            'archivo_id' => $archivo->id,
            'etiqueta_id' => $request->input('tag'),
        ]);

        return response()->json(['message' => 'Archivo subido correctamente e insertado en la base de datos'], 200);
    }
    // Función para validar archivo nombre y cambiarlo.
    private function validarNombre($fileName)
    {
        $fileName = preg_replace('/\s+/', ' ', $fileName);
        $fileName = str_replace(' ', '_', $fileName);
        $search = ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'];
        $replace = ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'];
        $fileName = str_replace($search, $replace, $fileName);
        $fileName = str_replace(['Ñ', 'ñ'], ['N', 'n'], $fileName);
        $fileName = preg_replace('/[°!"#$%&\/(){}=¿?¡¨*\[\];:|\'´\-\+,.\/\\@¬~`^]/', '', $fileName);
        return $fileName;
    }

     // Listar Archivos
     public function list(Request $request)
     {
         // Obtener archivos desde el modelo Archivo con paginación
         $files = File::paginate(10);
         $files->getCollection()->transform(function ($file) {
             return [
                 'name' => $file->nombre_archivo,
                 'url' => $file->ubicacion_archivo,
                 'estado' => $file->estado,
                 'publico' => $file->publico,
                 'usuarios_id' => $file->usuarios_id,
             ];
         });
         if ($request->wantsJson()) {
             return response()->json($files, 200);
         }
         return Inertia::render('Files/List', [
             'files' => $files,
         ]);
     }
}