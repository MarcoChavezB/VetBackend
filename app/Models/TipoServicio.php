<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoServicio extends Model
{
    use HasFactory;

    protected $fillable = [
        "id", "nombre_TServicio", "id_servicio", "descripcion", "precio", "estado"
    ];
    public $timestamps = false;
    public $table = "tipos_servicios";

}
