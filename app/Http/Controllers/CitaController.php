<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\PorcentajeCrecimientoCitas;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function getPorcentajeCitas(){
        $procentaje = PorcentajeCrecimientoCitas::all();

        return response()->json([
            'porcentaje' => $procentaje
        ]);
    }

    public function index(){
        $citas = DB::table('citas')
            ->select('citas.id', 'clientes.nombre', 'clientes.telefono1', 'citas.fecha_cita', 'citas.estatus', 'animales.raza')
            ->join('animales', 'animales.id', '=', 'citas.id_mascota')
            ->join('clientes', 'clientes.id', '=', 'animales.propietario')
            ->where('citas.estatus', 'pendiente')
            ->get();

        return response()->json([
            'citas' => $citas
        ]);
    }

    public function getCitasHoy(){
        $citas = DB::table('citas')
            ->select('citas.id', 'clientes.nombre', 'clientes.telefono1', 'citas.fecha_cita', 'citas.estatus', 'animales.raza')
            ->join('animales', 'animales.id', '=', 'citas.id_mascota')
            ->join('clientes', 'clientes.id', '=', 'animales.propietario')
            ->whereDate('citas.fecha_cita', now())
            ->get();

        return response()->json([
            'citas' => $citas
        ]);
    
    }

    public function vaidacionFechas(){
        $fechas = DB::table('validacionfechas')->get();

        return response()->json([
            'data' => $fechas
        ]);
    }
}
