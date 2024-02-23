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
        $data = DB::table('tipos_servicios')->get();
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
            $dc->consulta_id = $consulta->id;
            $dc->tservicios_id = $servicio;
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

    public function generarConsultaCliente(Request $request){
        $validate = Validator::make($request->all(), [
            'Nombre' => 'required|string',
            'Apellido' => 'required|string',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()
            ], 400);
        }

        $resultados = DB::select("CALL GenerarConsultasCliente(?, ?)", array($request->Nombre, $request->Apellido));

        return response()->json([
            'data' => $resultados
        ]);
    }

    public function buscarServicios(Request $request){
        $validate = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()
            ], 400);
        }

        $servicios = DB::table('tipos_servicios')
            ->select('id', 'nombre_TServicio')
            ->where('nombre_TServicio', 'like', '%'.$request->nombre.'%')
            ->get();

        if ($servicios->isEmpty()) {
            return response()->json([
                'msg' => 'No se encontraron servicios'
            ], 404);
        }

        return response()->json([
            'servicios' => $servicios
        ], 201);
    }

    public function buscarCitasCliente(Request $request)
    {
        $nombreCompleto = $request->input('nombre');
        $nombre = explode(' ', trim($nombreCompleto))[0];
        $apellido = substr($nombreCompleto, strlen($nombre) + 1);

        $resultados = DB::table('citas as ci')
            ->select('ci.id', 'a.nombre as Nombre', 'a.especie as Especie', 'a.raza as Raza', 'a.genero as Genero', DB::raw("CONCAT(u.nombre, ' ', ' ', u.apellido) as Dueño"), 'ci.fecha_cita as Fecha', 'ci.motivo as Motivo')
            ->leftJoin('animales as a', 'a.id', '=', 'ci.id_mascota')
            ->leftJoin('consultas as co', 'ci.id', '=', 'co.id_cita')
            ->leftJoin('usuarios as u', 'a.propietario', '=', 'u.id')
            ->where('ci.estatus', 'Aceptada')
            ->where('u.nombre', 'like', '%' . $nombre . '%')
            ->where('u.apellido', 'like', '%' . $apellido . '%')
            ->groupBy('ci.id', 'a.nombre', 'a.especie', 'a.raza', 'a.genero', DB::raw("CONCAT(u.nombre, ' ', ' ', u.apellido)"), 'ci.fecha_cita', 'ci.motivo')
            ->get();

        return $resultados;
    }


    public function reporteCitasRechazadasCliente(Request $request){
        $validate = Validator::make($request->all(), [
            'Nombre' => 'required|string',
            'Apellido' => 'required|string',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()
            ], 400);
        }

        $resultados = DB::select("CALL ReporteCitasRechazadasCliente(?, ?)", array($request->Nombre, $request->Apellido));

        return response()->json([
            'data' => $resultados
        ]);
    }

    public function reporteCitasRechazadasFecha(Request $request){
        $validate = Validator::make($request->all(), [
            'Fecha' => 'required|date',
            'Fecha2' => 'required|date',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()
            ], 400);
        }

        $resultados = DB::select("CALL ReporteCitasRechazadasFecha(?, ?)", array($request->Fecha, $request->Fecha2));

        return response()->json([
            'data' => $resultados
        ]);
    }
}
