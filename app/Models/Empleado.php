<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $filleable = [
        "nombre","apellido","correo","contrasena"
    ];

    protected $table = "empleados";
}
