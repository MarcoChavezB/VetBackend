<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Producto;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function getCitasProximas(){
        $citas = Cita::whereBetween('fecha_cita', [now(), now()->addDays(2)])
        ->get();
        return response()->json([
            'citas' => $citas
        ]);
    }

    public function citasTotalHoy(){
        $citas = Cita::whereDate('fecha_cita', now())->get();
        return response()->json([
            'citas' => $citas
        ]);
    }

    public function getProductosPocasExistencias(){
        $productos = Producto::where('existencias', '<', 5)->get();
        return response()->json([
            'productos' => $productos
        ]);
    }
}
