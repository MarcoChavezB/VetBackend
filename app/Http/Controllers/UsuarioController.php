<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use App\Models\Usuario;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    public function registro(Request $request){
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:4',
            'last_name' => 'required|string|max:255|min:4',
            'email' => 'required|email|max:100|unique:usuarios',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10|unique:usuarios',
            'phone2' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'password' => 'required|min:4',
            'confirm_password' => 'required|same:password',
        ]);

        if($validate->fails()){
            return response()->json([
                'errors' => $validate->errors()
            ], 400);
        }

        $user = new Usuario();
        $user->nombre = $request->name;
        $user->apellido = $request->last_name;
        $user->correo = $request->email;
        $user->telefono1 = $request->phone;
        $user->telefono2 = $request->phone2;
        $user->tipo_usuario = $request->tipo_usuario;
        $user->contra = Hash::make($request->password);
        $user->save();

        return response()->json([
            'msg' => 'Usuario registrado correctamente',
            'data' => $user
        ]);
    }


    public function login(Request $request){
        // login con SANCTUM

        $user = User::where('correo', $request->correo)->first();

        if(!$user){
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        if(! $user || !Hash::check($request->contra, $user->contra)){
            return response()->json([
                'msg' => 'No autorizado'
            ], 401);
        }

        $token = $user->createToken('Accesstoken')->plainTextToken;

        return response()->json([
            'msg' => 'Se ha logeado correctamente',
            'data' => $user,
            'jwt' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}


// pull a server
// cd /var/www/html/VetBackend
// sudo chown -R ubuntu:ubuntu /var/www/html
// git pull
// sudo chown -R www-data:www-data /var/www/html
// sudo systemctl restart apache2
