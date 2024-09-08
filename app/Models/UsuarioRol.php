<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioRol extends Model
{
    use HasFactory;

    protected $table = 'usuarios_roles';

    protected $fillable = [
        'usuario_id',
        'rol_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'rol_id');
    }
}
