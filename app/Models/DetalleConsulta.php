<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleConsulta extends Model
{
    use HasFactory;

    protected $fillable = [
        "id_consulta",
        "id_tservicios",


    ];

    public $timestamps = false;

    protected $table = "detalle_consultas";
}
