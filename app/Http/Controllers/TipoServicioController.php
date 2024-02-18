<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\TipoServicio; 
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class TipoServicioController extends Controller
{
    public function CrearTipoServicioYProductos(Request $request) {
        DB::beginTransaction();

        try {
            $tipoServicio = new TipoServicio();

            $tipoServicio->nombre_TServicio = $request->nombre_TServicio;
            $tipoServicio->id_servicio = $request->id_servicio;
            $tipoServicio->descripcion = $request->descripcion;
            $tipoServicio->precio = $request->precio;
            $tipoServicio->estado = $request->estado;

            $tipoServicio->save();

            if($tipoServicio) {
                $productos = $request->productos;
                foreach ($productos as $producto) {
                    DB::select('CALL InsertarProductoServicio(?, ?, ?)', [
                        $tipoServicio->id,
                        $producto['id'],
                        $producto['cantidad']
                    ]);
                }

                DB::commit();
                return response()->json(['message' => 'Tipo de servicio y productos asociados creados exitosamente.'], 200);
            } else {
                DB::rollBack();
                return response()->json(['message' => 'Hubo un error al crear el tipo de servicio y productos asociados.'], 400);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function serviciospublicos() {
        try {
            $resultados = DB::select("SELECT * FROM vista_servicios_publicos");

            return response()->json([
                'data' => $resultados
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function serviciosprivados() {
        try {
            $resultados = DB::select("SELECT * FROM vista_servicios_nopublicos");

            return response()->json([
                'data' => $resultados
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_UNAUTHORIZED); 
        }
    }

    public function serviciospublicosesteticos() {
        try {
            $resultados = DB::select("SELECT * FROM vista_servicios_publicos_esteticos");

            return response()->json([
                'data' => $resultados
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_UNAUTHORIZED); 
        }
    }

    public function serviciospublicosclinicos() {
        try {
            $resultados = DB::select("SELECT * FROM vista_servicios_publicos_clinicos");

            return response()->json([
                'data' => $resultados
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_UNAUTHORIZED); 
        }
    }


    public function publicarono(Request $request)
    {
        try {
            $idServicio = $request->input('id_servicio');

            $resultados = DB::select("CALL TipoServicioEstado(?)", [$idServicio]);

            return response()->json([
                'success' => true,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR); 
        }
    }

}
