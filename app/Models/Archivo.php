<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_archivo',      
        'ubicacion_archivo',   
        'estado',              
        'publico',             
        'usuarios_id',         
    ];
}
