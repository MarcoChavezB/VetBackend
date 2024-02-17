<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'correo',
        'telefono1',
        'telefono2',
        'contra',
        'tipo_usuario',
    ];

    protected $table = 'usuarios';

    protected $hidden = [
        'contra',
    ];

    public $timestamps = false;

}
