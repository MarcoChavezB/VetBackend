<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    public function registro(Request $request){
        $validate = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|min:4',
            'apellido' => 'required|string|max:255|min:4',
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|min:8',
        ]);
    }


    public function login(Request $request){
        // login con JWT
        $user = Usuario::where('correo', $request->correo)->where('contra', $request->contra)->first();

        if(!$user){
            return response()->json([
                'message' => 'Usuario no encontrado'
            ]);
        }

        $jwt = JWT::encode(['id', $user->id], env('JWT_SECRET'),'HS256');
        return response()->json([
            'msg' => 'Se ha logeado correctamente',
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
