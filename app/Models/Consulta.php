<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    use HasFactory;


    protected $fillable = [
        "id_cita",
        "observaciones",
        "peso_kg",
        "altura_mts",
        "edad_meses",
    ];

    public $timestamps = false;

    protected $table = "consultas";
}
