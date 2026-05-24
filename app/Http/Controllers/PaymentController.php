<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentController extends Controller
{
    /**
     * Crear un Stripe PaymentIntent para pagar el anticipo del 50% de la cita de forma embebida.
     */
    public function createPaymentIntent(Request $request, Appointment $appointment): JsonResponse
    {
        // Cargar los servicios asociados a la cita
        $appointment->load('services');

        // Calcular el costo total acumulado
        $totalCost = $appointment->services->sum('price');

        if ($totalCost <= 0) {
            return response()->json([
                'error' => 'La cita no tiene servicios asociados o el costo total es cero.'
            ], 400);
        }

        // El anticipo siempre es el 50% del total
        $advanceAmount = $totalCost * 0.50;

        // Stripe requiere el monto en centavos (ej: 100 pesos = 10000 centavos)
        $amountInCents = intval(round($advanceAmount * 100));

        try {
            // Establecer la clave secreta de Stripe
            Stripe::setApiKey(config('services.stripe.secret'));

            // Crear el PaymentIntent
            $intent = PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => 'mxn',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'appointment_id' => $appointment->id,
                    'amount_paid' => $advanceAmount,
                ],
            ]);

            // Guardar el PaymentIntent ID en la cita para poder rastrearlo
            $appointment->update([
                'stripe_session_id' => $intent->id,
                'payment_status' => 'pending',
            ]);

            return response()->json([
                'client_secret' => $intent->client_secret,
                'amount_to_pay' => $advanceAmount,
                'total_appointment_cost' => $totalCost,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al conectar con Stripe: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Endpoint temporal para pruebas rápidas de Stripe.
     * Crea un cliente, especialista, servicio y cita ficticios, y devuelve el client_secret.
     */
    public function testCheckout(Request $request): JsonResponse
    {
        // 1. Obtener o crear un cliente ficticio
        $client = \App\Models\User::firstOrCreate(
            ['email' => 'cliente_test@auraspa.com'],
            [
                'name' => 'Cliente de Prueba',
                'password' => bcrypt('password123'),
            ]
        );
        
        if (method_exists($client, 'assignRole')) {
            $client->assignRole('client');
        }

        // 2. Obtener o crear una cosmetóloga ficticia
        $specialistUser = \App\Models\User::firstOrCreate(
            ['email' => 'especialista_test@auraspa.com'],
            [
                'name' => 'Cosmetóloga de Prueba',
                'password' => bcrypt('password123'),
            ]
        );
        if (method_exists($specialistUser, 'assignRole')) {
            $specialistUser->assignRole('specialist');
        }

        $specialist = \App\Models\Specialist::firstOrCreate(
            ['user_id' => $specialistUser->id],
            [
                'specialty' => 'Masajes y Faciales',
                'active' => true,
            ]
        );

        // 3. Obtener o crear un servicio ficticio
        $service = \App\Models\Service::firstOrCreate(
            ['name' => 'Masaje Relajante Express (Prueba)'],
            [
                'description' => 'Servicio de masaje para probar la pasarela de Stripe.',
                'category' => 'Masajes',
                'price' => 600.00, // Total: $600 MXN, anticipo 50%: $300 MXN
                'duration' => 45,
            ]
        );

        // 4. Crear la cita ficticia
        $appointment = Appointment::create([
            'client_id' => $client->id,
            'specialist_id' => $specialist->id,
            'scheduled_date' => now()->addDays(1)->format('Y-m-d'),
            'time' => '12:00:00',
            'status' => 'pending',
        ]);

        // Asociar el servicio a la cita
        $appointment->services()->syncWithoutDetaching([$service->id]);

        // 5. Reutilizar la lógica de creación del PaymentIntent
        return $this->createPaymentIntent($request, $appointment);
    }
}
