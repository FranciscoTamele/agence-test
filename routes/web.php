<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\FinanceiroController;

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

Route::get('/', [HomeController::class,'index']);

// Rota link assuntos financeiros
Route::get('/financeiros', [FinanceiroController::class,'financeiro']);

// Rota consultores
Route::get('/d-consultores', [FinanceiroController::class,'consultores']);

// Rota clientes
Route::get('/d-clientes', [FinanceiroController::class,'clientes']);

Route::get('/desenpenho/consultores/relatorio', [FinanceiroController::class,'consultoresRelatorioDes']);

Route::get('/desenpenho/consultores/pizza', [FinanceiroController::class, 'consultoresPizzaDes']);

Route::get('/desenpenho/consultores/grafico', [FinanceiroController::class, 'consultoresGraficoDes']);


// Rotas desempenho dos clientes
Route::get('desenpenho/clientes/relatorio', [FinanceiroController::class, 'clientesRelatorioDesem']);

Route::get('desenpenho/clientes/grafico', [FinanceiroController::class, 'clientesGraficoDesem']);

Route::get('desenpenho/clientes/pizza', [FinanceiroController::class, 'clientesPizzaDesem']);
