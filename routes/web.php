<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DnaController;

Route::get('/', function () {
    return view('index');
});

// Verifica o DNA
Route::post('/verificar-dna', [DnaController::class, 'verificarDna']);

// Historico
Route::get('/exibir-historico', [DnaController::class, 'exibirHistorico']);

