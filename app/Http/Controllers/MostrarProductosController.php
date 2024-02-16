<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class MostrarProductosController extends Controller
{
    public function mostrarPorductosVenta(){
        $productosVenta = Producto::where('tipo_producto', 'venta')->get();
        return response()->json([
            'productos' => $productosVenta
        ]);
    }

    public function getProductoByName($name){
        $producto = Producto::where('nombre', 'like', '%'.$name.'%')->where('tipo_producto', 'venta')->get(); 
        return response()->json([
            'producto' => $producto
        ]);
    }
}
