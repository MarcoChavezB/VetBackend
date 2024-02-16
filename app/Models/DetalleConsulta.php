<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleConsulta extends Model
{
    use HasFactory;

    protected $filleable = [
        "id_consulta",
        "id_tservicios",


    ];

    protected $table = "detalle_consultas";
}
