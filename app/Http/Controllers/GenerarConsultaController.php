<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\DetalleConsulta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GenerarConsultaController extends Controller
{
    public function generarConsultas(){
        $data = DB::table('generarconsultas')->get();
        return response()->json([
            'data' => $data
        ]);
    }

    public function tServicios(){
        $data = DB::table('tservicios')->get();
        return response()->json([
            'data' => $data
        ]);
    }

    public function store(Request $request){
        $validate = Validator::make($request->all(), [
            'id_cita' => 'required|exists:citas,id|integer',
            'observaciones' => 'required|string|max:255|min:4',
            'peso_kg' => 'required',
            'altura_mts' => 'required',
            'edad_meses' => 'required',
            'servicios_id' => 'required|array',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()
            ], 400);
        }

        $consulta = new Consulta();
        $consulta->id_cita = $request->id_cita;
        $consulta->observaciones = $request->observaciones;
        $consulta->peso_kg = $request->peso_kg;
        $consulta->altura_mts = $request->altura_mts;
        $consulta->edad_meses = $request->edad_meses;
        $consulta->save();

        foreach ($request->servicios_id as $servicio) {
            $dc = new DetalleConsulta();
            $dc->id_consulta = $consulta->id;
            $dc->id_servicio = $servicio;
            $dc->save();
        }

        return response()->json([
            'msg' => 'Consulta registrada correctamente',
            'data' => $consulta
        ], 201);

    }

    public function calcularCostoDetallado(Request $request){
        $validate = Validator::make($request->all(), [
            'services' => 'required|array',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()
            ], 400);
        }

        $ids = implode(',', $request->services);

        $resultados = DB::select("CALL calcularCostoDetallado(?)", array($ids));

        return response()->json([
            'data' => $resultados
        ]);
    }
}
