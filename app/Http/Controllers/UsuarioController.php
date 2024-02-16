<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function registro(Request $request){
        $data = $request->all();
        
        response()->json([
            'data' => $data,
            'message' => 'Registro exitoso'
        ]);
    }
}


// pull a server 
// sudo chown -R www-data:www-data /var/www/html
// git pull
// sudo chown -R ubuntu:ubuntu /var/www/html
// sudo systemctl restart apache2
