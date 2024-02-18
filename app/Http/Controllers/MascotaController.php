<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use Illuminate\Support\Facades\Validator;

class MascotaController extends Controller
{
    public function store (Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50|min:4',
            'propietario' => 'required|exists:usuarios,id|integer',
            'especie' => 'required|string|max:50|min:4',
            'raza' => 'required|string|max:50|min:4',
            'genero' => 'required|string|max:50|min:4'
        ]);

        if($validate->fails()){
            return response()->json([
                'errors' => $validate->errors()
            ], 400);
        }

        $mascota = new Animal();
        $mascota->nombre = $request->nombre;
        $mascota->propietario = $request->propietario;
        $mascota->especie = $request->especie;
        $mascota->raza = $request->raza;
        $mascota->genero = $request->genero;
        $mascota->save();

        return response()->json([
            'msg' => 'Mascota registrada correctamente',
            'data' => $mascota
        ]);

    }


    public function index(int $id)
    {
        $mascotas = Animal::select('id', 'nombre')->where('propietario', $id)->get();
        if ($mascotas->isEmpty()) {
            return response()->json([
                'msg' => 'No hay mascotas registradas'
            ], 404);
        }
        return response()->json([
            'msg' => 'Lista de mascotas',
            'data' => $mascotas
        ], 201);
    }

    public function mascotasxusuario(Request $request) {

        $id_cliente = $request->input('id_cliente'); 

        if (!$id_cliente) {
            return response()->json([]);
        }
        $results = DB::select('CALL MascotasUsuario(:id_cliente)', ['id_cliente' => $id_cliente]);

        return response()->json($results);
    }
}
