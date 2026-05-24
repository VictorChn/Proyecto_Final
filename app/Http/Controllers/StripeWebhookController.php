<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    /**
     * Procesar las notificaciones asíncronas (Webhooks) enviadas por Stripe.
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            // Verificar la autenticidad y firma del Webhook
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (\UnexpectedValueException $e) {
            // Carga útil inválida
            return response()->json(['error' => 'Payload inválido'], 400);
        } catch (SignatureVerificationException $e) {
            // Firma inválida
            return response()->json(['error' => 'Firma del webhook inválida: ' . $e->getMessage()], 400);
        }

        // Manejar el evento de éxito del PaymentIntent
        if ($event->type === 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;

            // Extraer la información guardada en los metadatos de la sesión
            $appointmentId = $paymentIntent->metadata->appointment_id ?? null;
            $amountPaid = $paymentIntent->metadata->amount_paid ?? 0;

            if ($appointmentId) {
                $appointment = Appointment::find($appointmentId);

                if ($appointment) {
                    // Actualizar el estado del pago y de la cita
                    $appointment->update([
                        'payment_status' => 'paid',
                        'status' => 'confirmed',
                        'amount_paid' => $amountPaid,
                    ]);

                    // Cargar relaciones para el correo
                    $appointment->load(['client', 'services']);

                    // Enviar correo de confirmación
                    try {
                        \Illuminate\Support\Facades\Mail::to($appointment->client->email)
                            ->send(new \App\Mail\AppointmentConfirmed($appointment));
                    } catch (\Exception $e) {
                        logger('Error al enviar correo de confirmación tras pago: ' . $e->getMessage());
                    }

                    return response()->json([
                        'message' => 'Cita confirmada y pago del anticipo registrado con éxito. Correo enviado.',
                        'appointment_id' => $appointmentId
                    ], 200);
                }
            }

            return response()->json(['error' => 'Cita no encontrada en los metadatos del PaymentIntent.'], 404);
        }

        // Responder con un status 200 para indicarle a Stripe que se recibió el evento
        return response()->json(['status' => 'evento recibido y omitido'], 200);
    }
}
