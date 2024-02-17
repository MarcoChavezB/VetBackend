<?php

use App\Http\Controllers\CitaController;
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

// * Productos 
Route::get('/productos/venta', [MostrarProductosController::class, 'mostrarPorductosVenta']);
Route::get('/productos/getProductoByName/{name}', [MostrarProductosController::class, 'getProductoByName']);

// * Usuarios
Route::post('/usuario/registro', [UsuarioController::class, 'registro']);
Route::post('/usuario/login', [UsuarioController::class, 'login']); 


// * Ventas 
Route::post('/ventas/getRangoVentas', [VentaController::class, 'getVentasPorMes']);
Route::get('/ventas/graph/getPorcentaje', [VentaController::class, 'getPorcentajeVentas']);

// * Citas 
Route::get('/citas/getCitasProximas', [CitaController::class, 'getCitasProximas']);
Route::get('/citas/citasTotalHoy', [CitaController::class, 'citasTotalHoy']);
Route::get('/citas/getProductos/pocasExistencias', [CitaController::class, 'getProductosPocasExistencias']);

// pull a server 
// cd /var/www/html/VetBackend
// sudo chown -R ubuntu:ubuntu /var/www/html
// git pull
// sudo chown -R www-data:www-data /var/www/html
// sudo systemctl restart apache2
