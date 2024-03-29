<?php

namespace App\Http\Controllers;
use App\Mail\CitaAgendada;
use App\Models\Cita;
use App\Models\User;
use App\Models\Animal;
use App\Models\PorcentajeCrecimientoCitas;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class CitaController extends Controller
{

    public function citasProximas(){
        $citas = DB::select("SELECT
        citas.id,
        clientes.nombre,
        clientes.telefono1,
        citas.fecha_cita,
        citas.estatus,
        animales.raza
        from citas
            inner join animales on animales.id = citas. id_mascota
            inner join clientes on clientes.id = animales.propietario
            where citas.fecha_cita between now() and now() + interval 2 day");

        return response()->json([
            'citas' => $citas
        ]);
    }

    public function citasAceptadas(){
        $citas = DB::select("SELECT
        citas.id,
        clientes.nombre,
        clientes.telefono1,
        citas.fecha_cita,
        citas.estatus,
        animales.raza
        from citas
            inner join animales on animales.id = citas. id_mascota
            inner join clientes on clientes.id = animales.propietario
            where citas.estatus = 'Aceptada'");

        return response()->json([
            'citas' => $citas
        ]);
    }

    public function updateStatus(Request $request){
        $data = $request->all();
        $id = $data['cita_id'];
        $estatus = $data['cita_respuesta'];
        $motivo = $data['motivo'] ?? null;

        DB::select('Call cambiar_estatus_cita(?, ?, ?)', [$id, $estatus, $motivo]);

        return response()->json([
            'msg' => 'Estatus de la cita actualizado correctamente'
        ]);
    }


    public function getCitasProximas(){
        $citas = Cita::whereBetween('fecha_cita', [now(), now()->addDays(2)])
        ->get();
        return response()->json([
            'citas' => $citas
        ]);
    }

    public function getCitaById($id){
        $cita = DB::select("SELECT
        citas.id,
        citas.motivo,
        clientes.nombre,
        clientes.telefono1,
        citas.fecha_registro,
        citas.fecha_cita,
        citas.estatus,
        animales.raza
        FROM citas
            INNER JOIN animales ON animales.id = citas.id_mascota
            INNER JOIN clientes ON clientes.id = animales.propietario
        WHERE citas.id = :cita_id",
        ['cita_id' => $id,]);

        return response()->json([
            'cita' => $cita
        ]);
    }

    public function citasTotalHoy(){
        $citas = Cita::whereDate('fecha_cita', now())->count();
        return response()->json([
            'citas' => $citas
        ]);
    }

    public function getProductosPocasExistencias(){
        $productos = Producto::where('existencias', '<', 5)->get();
        return response()->json([
            'productos' => $productos
        ]);
    }

    public function getPorcentajeCitas(){
        $procentaje = PorcentajeCrecimientoCitas::all();

        return response()->json([
            'porcentaje' => $procentaje
        ]);
    }

    public function index(){
        $citas = DB::table('citas')
            ->select('citas.id', 'clientes.nombre', 'clientes.telefono1', 'citas.fecha_cita', 'citas.estatus', 'animales.raza')
            ->join('animales', 'animales.id', '=', 'citas.id_mascota')
            ->join('clientes', 'clientes.id', '=', 'animales.propietario')
            ->where('citas.estatus', 'pendiente')
            ->get();

        return response()->json([
            'citas' => $citas
        ]);
    }

    public function getCitasHoy(){
        $citas = DB::table('citas')
            ->select('citas.id', 'clientes.nombre', 'clientes.telefono1', 'citas.fecha_cita', 'citas.estatus', 'animales.raza')
            ->join('animales', 'animales.id', '=', 'citas.id_mascota')
            ->join('clientes', 'clientes.id', '=', 'animales.propietario')
            ->whereDate('citas.fecha_cita', now())
            ->get();

        return response()->json([
            'citas' => $citas
        ]);

    }

    public function vaidacionFechas(){
        $fechas = DB::table('validacionfechas')->get();

        return response()->json([
            'data' => $fechas
        ]);
    }

    public function store(Request $request){
        $validate = Validator::make($request->all(), [
            'fecha_cita' => 'required|date|after:'.Carbon::now(),
            'id_mascota' => 'required|exists:animales,id|integer',
            'estatus' => 'required|string|max:50|min:4',
            'motivo' => 'required|string|max:255|min:4'
        ]);

        if($validate->fails()){
            return response()->json([
                'errors' => $validate->errors()
            ], 400);
        }
        $user = Auth::user();

        $cita = new Cita();
        $cita->user_regis = $user->id;
        $cita->fecha_registro = date('Y-m-d H:i:s');
        $cita->fecha_cita = $request->fecha_cita;
        $cita->id_mascota = $request->id_mascota;
        $cita->estatus = $request->estatus;
        $cita->motivo = $request->motivo;
        $cita->save();

        $admins = DB::table('usuarios')->where('tipo_usuario', 'Administrador')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->correo)->send(new CitaAgendada($admin));
        }
        
        Mail::to($user->correo)->send(new CitaAgendada($user));

        return response()->json([
            'msg' => 'Cita registrada correctamente',
            'data' => $cita
        ], 201);
    }

    public function citasPendientes(int $id){
        $citas = Cita::join('animales', 'citas.id_mascota', '=', 'animales.id')
            ->join('usuarios', 'citas.user_regis', '=', 'usuarios.id')
            ->select('citas.id', 'citas.fecha_cita as Fecha', 'citas.estatus as Estatus', 'citas.motivo as Motivo', 'animales.nombre as Nombre', 'usuarios.nombre as cliente')
            ->where('user_regis', $id)
            ->where(function ($query) {
                $query->where('estatus', 'Pendiente')
                    ->orWhere('estatus', 'Aceptada');
            })
            ->get();
        if ($citas->isEmpty()) {
            return response()->json([
                'msg' => 'No hay citas pendientes'
            ], 404);
        }
        return response()->json([
            'msg' => 'Lista de citas pendientes',
            'data' => $citas
        ], 200);
    }

    public function citasRechazadas(int $id){
        $citas = Cita::join('animales', 'citas.id_mascota', '=', 'animales.id')
            ->join('usuarios', 'citas.user_regis', '=', 'usuarios.id')
            ->select('citas.id', 'citas.fecha_cita as Fecha', 'citas.estatus as Estatus', 'citas.motivo as Motivo', 'animales.nombre as Nombre', 'usuarios.nombre as cliente')
            ->where('user_regis', $id)
            ->where('estatus', 'Rechazada')
            ->get();
        if ($citas->isEmpty()) {
            return response()->json([
                'msg' => 'No hay citas rechazadas'
            ], 404);
        }
        return response()->json([
            'msg' => 'Lista de citas rechazadas',
            'data' => $citas
        ], 200);
    }




    public function CrearRegistroVeterinario(Request $request) {
        $user = Auth::user();

        DB::beginTransaction();

        try {
            $nuevoUsuario = User::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'telefono1' => $request->telefono1,
                'telefono2' => $request->telefono2,
            ]);
            $local_cliente_id = $nuevoUsuario->id;

            $nuevoAnimal = new Animal([
                'nombre' => $request->nombre_animal,
                'propietario' => $local_cliente_id,
                'especie' => $request->especie,
                'raza' => $request->raza,
                'genero' => $request->genero,
            ]);
            $nuevoAnimal->save();

            $nuevaCita = new Cita([
                'user_regis' => $user->id,
                'fecha_registro' => now(),
                'fecha_cita' => $request->fecha_cita,
                'id_mascota' => $nuevoAnimal->id,
                'estatus' => $request->estatus,
                'motivo' => $request->motivo,
            ]);
            $nuevaCita->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'cliente_id' => $local_cliente_id,
                    'animal_id' => $nuevoAnimal->id,
                    'cita_id' => $nuevaCita->id,
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }


}


