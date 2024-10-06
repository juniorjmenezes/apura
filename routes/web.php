<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SecaoController;
use App\Http\Controllers\CandidatoController;
use App\Http\Controllers\ApuracaoController;

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

// Seções
Route::get('/secoes', [SecaoController::class, 'index'])->name('secoes.index');
Route::get('/secoes/data', [SecaoController::class, 'getData'])->name('secoes.data');

// Candidatos
Route::get('/candidatos', [CandidatoController::class, 'index'])->name('candidatos.index');
Route::get('/candidatos/data', [CandidatoController::class, 'getData'])->name('candidatos.data');

// Votação
Route::get('/apuracao', [ApuracaoController::class, 'index'])->name('apuracao.index');
Route::post('/registrar-votos', [ApuracaoController::class, 'registrarVotos'])->name('apuracao.registrar.votos');
Route::get('/apuradas', [ApuracaoController::class, 'apuradas'])->name('apuradas.index');
Route::post('/atualizar-votos', [ApuracaoController::class, 'atualizarVotos'])->name('apuracao.atualizar.votos');
Route::get('/apuradas/data', [ApuracaoController::class, 'getData'])->name('apuradas.data');
Route::get('/painel', [ApuracaoController::class, 'painel'])->name('painel.index');
Route::get('/checar-atualizacoes', [ApuracaoController::class, 'checarAtualizacoes'])->name('painel.checar.atualizacoes');