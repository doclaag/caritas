<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileTag extends Model
{
    use HasFactory;

    protected $table = 'archivos_etiquetas';

    protected $fillable = [
        'archivo_id',
        'etiqueta_id',
    ];

    public function archivo()
    {
        return $this->belongsTo(File::class, 'archivo_id');
    }

    public function etiqueta()
    {
        return $this->belongsTo(Tag::class, 'etiqueta_id');
    }
}