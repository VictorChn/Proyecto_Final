<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Models\Service;

Route::get('/services', function () {
    $services = Service::all();
    return view('services', compact('services'));
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
    })->name('admin.users');

    Route::get('/admin/servicios', function(){
        return view('admin.services');
    })->name('admin.services');
});


Route::middleware(['auth:sanctum', 'role:Cliente'])->group(function(){
    Route::get('/seleccionar-servicios', function(){
        return view('sclient.seleccionar-servicios');
    })->name('seleccionar-servicios');

    Route::get('/historial-citas', function(){
        $user = auth()->user();
        $completedAppointments = \App\Models\Appointment::where('client_id', $user->id)
            ->whereIn('status', ['completed', 'realizada', 'cancelled', 'cancelada'])
            ->with(['specialist.user', 'services'])
            ->orderBy('scheduled_date', 'desc')
            ->orderBy('time', 'desc')
            ->get();

        return view('sclient.historial-citas', compact('completedAppointments'));
    })->name('sclient.historial-citas');

    Route::get('/historial-citas/{appointment}/ticket', [\App\Http\Controllers\TicketController::class, 'download'])
        ->name('ticket.download');
});