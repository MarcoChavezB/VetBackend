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
}
