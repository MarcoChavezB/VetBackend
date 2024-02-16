<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenProveedor extends Model
{
    use HasFactory;

    protected $filleable = [
        "id_orden", "id_proveedor"
    ];

    protected $table = "orden_proveedores";
}
