<?php

use App\Http\Controllers\CepController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API - ViaCEP
|--------------------------------------------------------------------------
*/

Route::get('/cep/{cep}', [CepController::class, 'show'])
    ->where('cep', '[0-9\-]+')
    ->name('api.cep.show');