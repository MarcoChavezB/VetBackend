<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function registro(Request $request){
        return response()->json([
            'message' => 'Registro de usuario'
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

        return response()->json([
            'message' => 'Usuario encontrado',
            'user' => $user->id
        ]);

        // $jwt = JWT::encode(['id', $user->id], env('JWT_SECRET'),'HS256');
        // return response()->json([
        //     'msg' => 'Se ha logeado correctamente',
        //     'jwt' => $jwt
        // ]);
    }
}


// pull a server 
// cd /var/www/html/VetBackend
// sudo chown -R ubuntu:ubuntu /var/www/html
// git pull
// sudo chown -R www-data:www-data /var/www/html
// sudo systemctl restart apache2
