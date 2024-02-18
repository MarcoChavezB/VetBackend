<?php

use App\Http\Controllers\CitaController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\MostrarProductosController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\TipoServicioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::any('/authenticate', function (Request $request) {
    return response()->json(['error' => 'Token inválido'], 401);
})->name('error');

Route::name('usuarios.')->prefix('/usuario')->name('usuario')->group(function () {
    Route::post('/registro', [UsuarioController::class, 'registro'])->name('registro');
    Route::post('/login', [UsuarioController::class, 'login'])->name('login');
});
Route::middleware(['auth:sanctum'])->group(function () {

    Route::name('usuarios.')->prefix('/usuario')->name('usuario')->group(function () {
        Route::post('/logout', [UsuarioController::class, 'logout'])->name('logout');
        Route::get('/getPets/{id}', [UsuarioController::class, 'getPets'])->where('id', '[0-9]+');
        Route::get('/getAdministradores', [UsuarioController::class, 'getAdministradores']);
        Route::get('/exist/{email}', [UsuarioController::class, 'existUser']);
    });

    Route::name('productos')->prefix('/productos')->group(function () {
        Route::get('/venta', [MostrarProductosController::class, 'mostrarPorductosVenta']);
        Route::get('/getProductoByName/{name}', [MostrarProductosController::class, 'getProductoByName']);
        Route::get('/productosPublicos/index', [MostrarProductosController::class, 'indexPublic']);
        Route::get('/productosInternos/index', [MostrarProductosController::class, 'indexInternos']);
        Route::get('/productosPublicos/getProductoByName/{name?}', [MostrarProductosController::class, 'getProductoPublicoByName']);
        Route::post('/productosPublicos/rango', [MostrarProductosController::class, 'getProductosRango']);
        Route::get('/productosInternos/getProductoByName/{name?}', [MostrarProductosController::class, 'getProductoInternoByName']);
        Route::get('/getCategorias', [MostrarProductosController::class, 'getCategorias']);
        Route::post('/store', [MostrarProductosController::class, 'store']);
        Route::get('/existe/{name}', [MostrarProductosController::class, 'existencia']);
        Route::get('/getProductoById/{id}', [MostrarProductosController::class, 'getProductoById']);
        Route::post('/update/one', [MostrarProductosController::class, 'updateOne']);
        Route::put('/update', [MostrarProductosController::class, 'update']);
        Route::delete('/delete/{id}', [MostrarProductosController::class, 'disableProduct']);
        Route::post('/productoxcadena', [MostrarProductosController::class, 'productoporcadena'])->name('productoxcadena');
        Route::get('/getProductos/existencias/{name}', [MostrarProductosController::class, 'getProductosExistencias']);
        Route::post('/ventaProductos', [MostrarProductosController::class, 'ventaProductos']);
        Route::get('/GenerarTiket', [MostrarProductosController::class, 'generarTiket']);
    });

    Route::name('ventas')->prefix('/ventas')->group(function () {
        Route::post('/getRangoVentas', [VentaController::class, 'getVentasPorMes']);
        Route::get('/graph/getPorcentaje', [VentaController::class, 'getPorcentajeVentas']);
        Route::get('/graph/getPorcentaje/monto', [VentaController::class, 'getPorcentajeMontoVentas']);
    });

    Route::name('cita')->prefix('/citas')->group(function () {
        Route::get('/getCitasProximas', [CitaController::class, 'getCitasProximas']);
        Route::get('/citasTotalHoy', [CitaController::class, 'citasTotalHoy']);
        Route::get('/getProductos/pocasExistencias', [CitaController::class, 'getProductosPocasExistencias']);
        Route::get('/graph/getPorcentaje', [CitaController::class, 'getPorcentajeCitas']);
        Route::get('/index', [CitaController::class, 'index']);
        Route::get('/validacionFechas', [CitaController::class, 'vaidacionFechas']);
        Route::get('/index/getCitasHoy', [CitaController::class, 'getCitasHoy']);
        Route::get('/index/cita/{id}', [CitaController::class, 'getCitaById']);
        Route::post('/store', [CitaController::class, 'store']);
        Route::put('/update/status', [CitaController::class, 'updateStatus']);
        Route::get('/citasPendientes/{id}', [CitaController::class, 'citasPendientes'])->where('id', '[0-9]+');
        Route::get('/citasRechazadas/{id}', [CitaController::class, 'citasRechazadas'])->where('id', '[0-9]+');
        Route::get('/citasAceptadas', [CitaController::class, 'citasAceptadas']);
        Route::get('/citasProximas', [CitaController::class, 'citasProximas']);

    });

    Route::name('mascotas.')->prefix('/mascotas')->group(function () {
        Route::post('/store', [MascotaController::class, 'store'])->name('store');
        Route::get('/index/{id}', [MascotaController::class, 'index'])->name('index')->where('id', '[0-9]+');
    });

    Route::name('clientes.')->prefix('/clientes')->group(function () {
        Route::post('/infoCorreo', [ClienteController::class, 'buscarPorCorreo'])->name('infoCorreo');
        Route::post('/actualizar', [ClienteController::class, 'update'])->name('actualizar');
        Route::get('/infoID', [ClienteController::class, 'obtenerClientePorID'])->name('infoID');
    });

    Route::name('servicios.')->prefix('/servicios')->group(function () {
        Route::get('/publicos', [TipoServicioController::class, 'serviciospublicos'])->name('publicos');
        Route::get('/privados', [TipoServicioController::class, 'serviciosprivados'])->name('privados');
        Route::get('/serviciospublicosesteticos', [TipoServicioController::class, 'serviciospublicosesteticos'])->name('serviciospublicosesteticos');
        Route::get('/serviciospublicosclinicos', [TipoServicioController::class, 'serviciospublicosclinicos'])->name('serviciospublicosclinicos');
        Route::post('/publicarono', [TipoServicioController::class, 'publicarono'])->name('publicarono');
        Route::post('/agregarservicioproduct', [TipoServicioController::class, 'CrearTipoServicioYProductos'])->name('agregarservicioproduct');

    });



});



Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/test/sanctum', function (){
        return response()->json(['msg' => 'Sanctum']);
    });
});


// pull a server
// cd /var/www/html/VetBackend
// sudo chown -R ubuntu:ubuntu /var/www/html
// git pull
// sudo chown -R www-data:www-data /var/www/html
// sudo systemctl restart apache2
