<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,doc,docx|max:10240', //max 10 mg
        ]);

        $file = $request->file('file');
        $destinationPath = 'public/CARITASPRUEBASDOCS';

        if (!Storage::exists($destinationPath)) {
            Storage::makeDirectory($destinationPath);
        }

        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs($destinationPath, $fileName);

        return response()->json(['message' => 'Archivo subido correctamente'], 200);
    }
}
