<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class MostrarProductosController extends Controller
{
    public function mostrarPorductosVenta(){
        $productosVenta = Producto::where('tipo_producto', 'venta')->get();
        return response()->json([
            'productos' => $productosVenta
        ]);
    }

    public function getProductoByName($name){
        $producto = Producto::where('nom_producto', 'like', '%'.$name.'%')->where('tipo_producto', 'venta')->get(); 
        return response()->json([
            'producto' => $producto
        ]);
    }

    public function indexPublic(){
        $resultado = DB::table('productos')
        ->select('id', 'nom_producto', 'descripcion', 'tipo_producto', 'imagen')
        ->selectRaw('MAX(existencias) as existencias')
        ->selectRaw('MAX(precio_venta) as precio_venta')
        ->selectRaw('(MAX(precio_venta) * 0.16) as iva')
        ->selectRaw("CASE WHEN MAX(existencias) <= 0 THEN 'Sin stock' ELSE 'Stock' END as estado")
        ->where('tipo_producto', 'venta')
        ->groupBy('nom_producto')
        ->get();

        return response()->json([
            'productos' => $resultado
        ]);
    }
}
