<?php

use App\Http\Controllers\TareaController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('usuarios/login', [UserController::class, "login"])->name('usuarios.login');

Route::post('usuarios', [UserController::class, "store"])->name('usuarios');

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::resource('tareas', TareaController::class)->except('create')->names('tareas');

    Route::get('logout', [UserController::class, "logout"])->name('logout');
});
