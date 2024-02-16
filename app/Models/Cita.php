<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $filleable = [

        "id",
        "user_regis",
        "fecha_registro",
        "fecha_cita",
        "id_mascota",
        "estatus",
        "motivo",
    ];

    protected $table = "citas";
}
