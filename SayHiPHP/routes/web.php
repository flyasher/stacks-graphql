<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PizzaController;



Route::get('/', function () {
    return view('welcome');
});
Route::get('/pizzas', [PizzaController::class, 'index'])->name('pizzas')->middleware('auth');
Route::get('/pizzas/{id}',[PizzaController::class, 'show'])->middleware('auth');
Route::get('/create', [PizzaController::class, 'create'])->name('create');
Route::post('/pizzas', [PizzaController::class, 'store'])->name('store');
Route::delete('/pizzas/{id}', [PizzaController::class, 'destroy'])->name('destroy')->middleware('auth');

Auth::routes();
