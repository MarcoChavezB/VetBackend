<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $filleable = [
        "nombre","apellido","correo","telefono1","telefono2","contraseña"
     ];
 
     protected $table = "clientes";
}
