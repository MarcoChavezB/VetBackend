<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function registro(Request $request){
        $data = $request->all();
        
        response()->json([
            'data' => $data
        ]);
    }
}
