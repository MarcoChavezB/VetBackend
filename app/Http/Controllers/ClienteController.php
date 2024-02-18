<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function buscarPorCorreo(Request $request) {

        $cadena = $request->input('cadena'); 

        if (!$cadena) {
            return response()->json([]);
        }
        $results = DB::select('CALL BuscarPorCampos(:cadena)', ['cadena' => $cadena]);

        return response()->json($results);
    }

    public function update(Request $request) {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['msg' => 'Usuario no encontrado'], 404);
        };
    
        $validate = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|min:4',
            'apellido' => 'required|string|max:255|min:4',
            'correo' => 'required|email|max:100|unique:usuarios,correo,' . $user->id,
            'telefono1' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10|unique:usuarios,telefono1,' . $user->id,
            'telefono2' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'contra' => 'nullable|min:4',
            'confirm_password' => 'nullable|same:contra',
        ]);
    
        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 400);
        }
    
        $user->nombre = $request->nombre;
        $user->apellido = $request->apellido;
        $user->correo = $request->correo;
        $user->telefono1 = $request->telefono1;
        $user->telefono2 = $request->telefono2; 
    
        if ($request->filled('contra')) {
            $user->contra = Hash::make($request->contra);
        }
        
        $user->save();
    
        return response()->json([
            'msg' => 'Se ha actualizado correctamente el usuario',
            'data' => $user
        ]);
    }

    public function obtenerClientePorID(){
        $user = Auth::user();
        if (!$user) {
            return response()->json(['msg' => 'Usuario no encontrado'], 404);
        };
        return response()->json($user);
    }
    
}
