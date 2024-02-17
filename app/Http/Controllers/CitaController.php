<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function getCitasProximas(){
        $citas = Cita::whereBetween('fecha_cita', [now(), now()->addDays(2)])
        ->toSql();
    
        return response()->json([
            'citas' => $citas
        ]);
    }
}
