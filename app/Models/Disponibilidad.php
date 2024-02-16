<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disponibilidad extends Model
{
    use HasFactory;

    protected $filleable = [
        "fecha", "hora_inicio", "hora_fin", "id_empleado"
    ];

    protected $table = "disponibilidades";

}
