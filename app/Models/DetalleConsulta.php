<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleConsulta extends Model
{
    use HasFactory;

    protected $fillable = [
        "consulta_id",
        "tservicios_id",


    ];

    public $timestamps = false;

    protected $table = "detalle_consultas";
}
