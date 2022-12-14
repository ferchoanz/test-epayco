<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('clientes', 'App\Http\Controllers\ClienteController@registro_cliente');
Route::post('clientes/recarga', 'App\Http\Controllers\ClienteController@recarga_billetera');
Route::post('clientes/consultar', 'App\Http\Controllers\ClienteController@consultar_saldo');
Route::post('clientes/pagar', 'App\Http\Controllers\ClienteController@pagar');
Route::post('clientes/confirmar', 'App\Http\Controllers\ClienteController@confirmar_pago');
