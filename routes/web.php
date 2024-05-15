<?php
use App\Http\Controllers\UpdateShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//ruta  http://127.0.0.1:8000/updateProducst
Route::get('/updateProducst', UpdateShopController::class);