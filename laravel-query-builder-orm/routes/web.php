<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsultasController;

Route::get('/', function () {
    return view('welcome');
});

// Rutas para consultas de Query Builder y ORM
Route::get('/consultas', [ConsultasController::class, 'index']);
Route::get('/consultas/pedidos-usuario-2', [ConsultasController::class, 'pedidosUsuario2']);
Route::get('/consultas/pedidos-con-usuarios', [ConsultasController::class, 'pedidosConUsuarios']);
Route::get('/consultas/pedidos-rango-precio', [ConsultasController::class, 'pedidosRangoPrecio']);
Route::get('/consultas/usuarios-con-r', [ConsultasController::class, 'usuariosConR']);
Route::get('/consultas/contar-pedidos-usuario-5', [ConsultasController::class, 'contarPedidosUsuario5']);
Route::get('/consultas/pedidos-ordenados-desc', [ConsultasController::class, 'pedidosOrdenadosDesc']);
Route::get('/consultas/suma-total-pedidos', [ConsultasController::class, 'sumaTotalPedidos']);
Route::get('/consultas/pedido-mas-economico', [ConsultasController::class, 'pedidoMasEconomico']);
Route::get('/consultas/pedidos-agrupados', [ConsultasController::class, 'pedidosAgrupadosPorUsuario']);
