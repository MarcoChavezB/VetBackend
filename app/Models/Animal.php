<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    use HasFactory;

    protected $filleable = [
        "nombre",
        "propietario",
        "especie",
        "raza",
        "genero"
    ];

    protected $hidden = [
        "created_at",
        "updated_at"
    ];

    protected $table = "animales";
}
