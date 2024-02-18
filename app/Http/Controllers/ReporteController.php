<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReporteController extends Controller
{
    public function reporteConsultas(Request $request){
        $validate = Validator::make($request->all(), [
            'nomC' => 'required|string',
            'apellidos' => 'required|string',
            'nomM' => 'required|string',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()
            ], 400);
        }

        $resultados = DB::select("CALL ReporteConsultas(?, ?, ?)", array($request->nomC, $request->apellidos, $request->nomM));
        return response()->json([
            'data' => $resultados
        ]);
    }

    public function reporteConsultasFecha(Request $request){
        $validate = Validator::make($request->all(), [
            'Fecha' => 'required|date',
            'Fecha2' => 'required|date',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()
            ], 400);
        }

        $resultados = DB::select("CALL ReporteConsultasFecha(?, ?)", array($request->Fecha, $request->Fecha2));
        return response()->json([
            'data' => $resultados
        ]);
    }

    public function historialMascota(int $id){
        $resultados = DB::select("CALL HistorialIDMascota(?)", array($id));
        return response()->json([
            'data' => $resultados
        ]);

    }
}
