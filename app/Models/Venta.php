<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $filleable = [
        "fecha","id_cliente","tipo_pago","monto_pagado"
     ];
 
     protected $table = "ventas";
}
