<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    
    protected $filleable = [
        "nom_producto", "descripcion", "existencias", "precio_venta", 
        "id_categoria", "id_proveedor", "precio_compra"
    ];

    public $table = "productos";
}
