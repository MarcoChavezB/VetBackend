<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function registro(Request $request){
        return response()->json([
            'message' => 'Registro de usuario'
        ]);
    }


    public function login(Request $request){
        // login usando sanctum

        $user = Usuario::where('correo', $request->correo)->where('contra', $request->contra)->first();

        if(!$user){
            return response()->json([
                'message' => 'No se encontro un usuario con esas credenciales'
            ]);
        }

        $sanToken = $user->createToken('sanctum_token')->plainTextToken;
        
        return response()->json([
            'message' => 'Usuario logeado',
            'token' => $sanToken
        ]);
    }
}


// pull a server 
// cd /var/www/html/VetBackend
// sudo chown -R ubuntu:ubuntu /var/www/html
// git pull
// sudo chown -R www-data:www-data /var/www/html
// sudo systemctl restart apache2
