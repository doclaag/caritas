<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Archivo;
use Illuminate\Support\Facades\Auth;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('file');
        $destinationPath = 'public/CARITASPRUEBASDOCS';
        if (!Storage::exists($destinationPath)) {
            Storage::makeDirectory($destinationPath);
        }
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $cleanName = $this->validarNombre($originalName);
        $fileName = $cleanName . '.' . $extension;
        $file->storeAs($destinationPath, $fileName);
        $filePath = Storage::url($destinationPath . '/' . $fileName);
        //Condición valida que el usuario este autenticado y de esta forma extrae el ID 
        if (Auth::check()) {
            $userId = Auth::id();
        } else {
            return response()->json(['error' => 'No autenticado'], 401);
        }
        // Insert a la tabla archivos
        Archivo::create([
            'nombre_archivo' => $fileName,
            'ubicacion_archivo' => $filePath,
            'estado' => 1,
            'publico' => $request->has('publico') ? 1 : 0,
            'usuarios_id' => $userId,
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
}
