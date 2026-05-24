<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StripeWebhookController;

// Rutas Públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);
Route::get('/test-stripe-payment', [PaymentController::class, 'testCheckout']);

// Rutas Protegidas (Autenticadas con Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    
    // Endpoint para ver el usuario actual
    Route::get('/me', function (Request $request) {
        return $request->user();
    });

    // Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout']);

    // Grupo de Rutas Exclusivas para Administradores
    Route::middleware('role:admin')->group(function () {
        // Aquí irán las rutas CRUD de staff, servicios, etc. de las Fases 4 y 5
        // Route::apiResource('staff', StaffController::class);
    });

    // Grupo de Rutas Exclusivas para Especialistas
    Route::middleware('role:specialist')->group(function () {
        // Aquí irán las rutas para ver su propia agenda, etc.
    });

    // Grupo de Rutas Exclusivas para Clientes
    Route::middleware('role:client')->group(function () {
        // Aquí irán las rutas para que el cliente agende sus citas
        Route::post('/appointments/{appointment}/checkout', [PaymentController::class, 'createCheckoutSession']);
    });
});
