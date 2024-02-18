<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    
    protected $filleable = [
        "nom_producto", "descripcion", "precio_compra", "tipo_producto", 
        "existencias", "precio_venta", "id_categoria", "imagen", "disabled"
    ];

    public $timestamps = false;
    public $table = "productos";    
}
