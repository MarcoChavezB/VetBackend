<?php

namespace App\Http\Controllers;

use App\Models\PorcentajeCrecimientoVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function getVentasPorMes(Request $request){
        $fechaI = $request->fechaI;
        $fechaF = $request->fechaF;

        $resultado = DB::table('ventas')
        ->select('ventas.fecha', DB::raw('COUNT(ventas.id) AS cantidad'))
        ->whereBetween('ventas.fecha', [$fechaI, $fechaF])
        ->groupBy('ventas.fecha')
        ->get();

        return response()->json([
            'ventas' => $resultado
        ]);
    }
    public function getPorcentajeVentas(){
        $porcentaje = PorcentajeCrecimientoVenta::all();
        return response()->json([
            'porcentaje' => $porcentaje
        ]);
    }
}
