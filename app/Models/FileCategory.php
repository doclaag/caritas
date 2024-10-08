<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileCategory extends Model
{
    use HasFactory;

    protected $table = 'archivos_categorias';

    protected $fillable = [
        'archivo_id',
        'categoria_id',
        'usuarios_id',
    ];
}