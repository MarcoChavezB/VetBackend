<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $filleable = [
        "id_venta","id_producto","cantidad","precio_unitario","subtotal"
     ];
 
     protected $table = "detalles_venta";
}
