<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/services', function () {
    return view('services');
})->name('services');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->hasRole('Administrador')){
            return view('admin.dashboard');
        } elseif ($user->hasRole('Estilista')){
            return view('stylist.dashboard');
        } else {
            return view('sclient.dashboard');
        }
    })->name('dashboard');
});

Route::middleware(['auth:sanctum', 'role:Administrador'])->group(function(){
    Route::get('/admin/usuarios', function(){
        return view('admin.users');
    });
});


Route::middleware(['auth:sanctum', 'role:Cliente'])->group(function(){
    Route::get('/agendar', function(){
        return view('sclient.agendar');
    })->name('agendar');
});