<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    public function registro(Request $request){
        $validate = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|min:4',
            'apellido' => 'required|string|max:255|min:4',
            'correo' => 'required|email|max:100|unique:usuarios',
            'telefono1' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'telefono2' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'contra' => 'required|min:4',
        ]);

        if($validate->fails()){
            return response()->json([
                'errors' => $validate->errors()
            ], 400);
        }

        $user = new Usuario();
        $user->nombre = $request->nombre;
        $user->apellido = $request->apellido;
        $user->correo = $request->correo;
        $user->telefono1 = $request->telefono1;
        $user->tipo_usuario = $request->tipo_usuario;
        $user->contra = Hash::make($request->contra);
        $user->save();

        return response()->json([
            'msg' => 'Usuario registrado correctamente',
            'data' => $user
        ]);
    }


    public function login(Request $request){
        // login con JWT
        $user = Usuario::where('correo', $request->correo)->where('contra', $request->contra)->first();

        if(!$user){
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $jwt = JWT::encode(['id' => $user->id], env('JWT_SECRET'), 'HS256');
        return response()->json([
            'msg' => 'Se ha logeado correctamente',
            'data' => $user,
            'jwt' => $jwt
        ]);
    }
}


// pull a server
// cd /var/www/html/VetBackend
// sudo chown -R ubuntu:ubuntu /var/www/html
// git pull
// sudo chown -R www-data:www-data /var/www/html
// sudo systemctl restart apache2
