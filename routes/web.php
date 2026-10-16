<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ModuloController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'form'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [ModuloController::class, 'inicio'])->name('inicio');
    Route::get('/m/{modulo}', [ModuloController::class, 'index'])->name('modulo.index');
    Route::get('/m/{modulo}/crear', [ModuloController::class, 'create'])->name('modulo.create');
    Route::post('/m/{modulo}', [ModuloController::class, 'store'])->name('modulo.store');
    Route::get('/m/{modulo}/{id}/editar', [ModuloController::class, 'edit'])->name('modulo.edit');
    Route::put('/m/{modulo}/{id}', [ModuloController::class, 'update'])->name('modulo.update');
    Route::delete('/m/{modulo}/{id}', [ModuloController::class, 'destroy'])->name('modulo.destroy');
});
