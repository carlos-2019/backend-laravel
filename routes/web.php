<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// RUTAS DEL API

    // RUTAS DEL CONTROLADOR DE CLIENTES
    Route::post('api/register', ['\App\Http\Controllers\clientesController','register']);
    Route::post('api/login', ['\App\Http\Controllers\clientesController','login']);
    Route::put('api/cliente/update', ['\App\Http\Controllers\clientesController','update']);
    Route::get('api/cliente/detalle/{id}', ['\App\Http\Controllers\clientesController','detalle']);
    Route::put('api/cliente/delete/{id}', ['\App\Http\Controllers\clientesController','delete']);
    Route::get('api/cliente/listar', ['\App\Http\Controllers\clientesController','listar']);

    // RUTAS DEL CONTROLADOR CATEGORIAS
    Route::get('api/categoria/listar', ['\App\Http\Controllers\categoriasController','listar']);

    // RUTAS DEL CONTROLADOR PRODUCTOS
    Route::get('api/producto/listar', ['\App\Http\Controllers\productosController','listar']);
    Route::get('api/producto/listarPorId/{id}', ['\App\Http\Controllers\productosController','listarPorId']);
    Route::post('api/producto/crear', ['\App\Http\Controllers\productosController','crear']);
    Route::put('api/producto/update/{id}', ['\App\Http\Controllers\productosController','update']);
    Route::put('api/producto/delete/{id}', ['\App\Http\Controllers\productosController','delete']);
    Route::get('/api/producto/getProductoByCategory/{id}', ['\App\Http\Controllers\productosController', 'getProductoByCategory']);
    Route::get('/api/producto', ['\App\Http\Controllers\productosController', 'buscarProducto']);

    // RUTAS DEL CONTROLADOR STAND
    Route::get('api/stand/listar', ['\App\Http\Controllers\standController','listar']);
    Route::get('api/stand/listarPorId/{id}', ['\App\Http\Controllers\standController','listarPorId']);
    Route::post('api/stand/crear', ['\App\Http\Controllers\standController','crear']);
    Route::put('api/stand/update/{id}', ['\App\Http\Controllers\standController','update']);
    Route::put('api/stand/delete/{id}', ['\App\Http\Controllers\standController','delete']);
    Route::get('/api/stand', ['\App\Http\Controllers\standController', 'buscarStand']);
    Route::get('/api/stand/ruc', ['\App\Http\Controllers\standController', 'buscarStandPorRuc']);
    // RUTAS DEL CONTROLADOR ORDENES 
    Route::get('api/ordenes/listar', ['\App\Http\Controllers\ordenesController','listar']);
    Route::get('api/ordenes/listarPorId/{id}', ['\App\Http\Controllers\ordenesController','listarPorId']);
    Route::post('api/ordenes/crear', ['\App\Http\Controllers\ordenesController','crear']);
    Route::put('api/ordenes/update/{id}', ['\App\Http\Controllers\ordenesController','update']);
    Route::put('api/ordenes/delete/{id}', ['\App\Http\Controllers\ordenesController','delete']);
    Route::get('/api/ordenes/getOrdenesByCliente/{id}', ['\App\Http\Controllers\ordenesController', 'getOrdenesByCliente']);

    // RUTAS DEL CONTROLADOR DETALLE ORDENES
    Route::get('/api/dordenes/getDetalleOrdenByOrden/{id}', ['\App\Http\Controllers\detalle_ordenController', 'getDetalleOrdenByOrden']);