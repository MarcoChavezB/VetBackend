<?php

use App\Http\Controllers\CitaController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\MostrarProductosController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VentaController;
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
//Route::middleware(['auth:sanctum'])->group(function () {

    Route::name('usuarios.')->prefix('/usuario')->name('usuario')->group(function () {
        Route::post('/logout', [UsuarioController::class, 'logout'])->name('logout');
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
    });

    Route::name('mascotas.')->prefix('/mascotas')->group(function () {
        Route::post('/store', [MascotaController::class, 'store'])->name('store');
        Route::get('/index/{id}', [MascotaController::class, 'index'])->name('index')->where('id', '[0-9]+');
    });


//});



// pull a server
// cd /var/www/html/VetBackend
// sudo chown -R ubuntu:ubuntu /var/www/html
// git pull
// sudo chown -R www-data:www-data /var/www/html
// sudo systemctl restart apache2
