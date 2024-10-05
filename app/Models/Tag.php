<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'etiquetas';

    protected $fillable = [
        'nombre_etiqueta',
        'descripcion_etiqueta',
        'estado',
        'usuarios_id',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuarios_id');
    }
}