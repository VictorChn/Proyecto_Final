<?php

namespace App\Services;

use App\Models\User;
use App\Models\PushSubscription;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class PushNotificationService
{
    /**
     * Enviar una notificación push a un usuario específico (por ID o Modelo User).
     */
    public static function send($userOrUserId, string $title, string $body, string $url = '/dashboard'): bool
    {
        $userId = $userOrUserId instanceof User ? $userOrUserId->id : $userOrUserId;

        if (!$userId) {
            return false;
        }

        // Obtener las suscripciones push de este usuario
        $subscriptions = PushSubscription::where('user_id', $userId)->get();

        if ($subscriptions->isEmpty()) {
            return false;
        }

        return self::dispatch($subscriptions, $title, $body, $url);
    }

    /**
     * Enviar una notificación push a todos los usuarios con un rol específico (ej: 'Administrador').
     */
    public static function sendToRole(string $roleName, string $title, string $body, string $url = '/dashboard'): bool
    {
        // Obtener usuarios con el rol especificado
        $users = User::role($roleName)->get();

        if ($users->isEmpty()) {
            return false;
        }

        $userIds = $users->pluck('id');

        // Obtener todas las suscripciones push de estos usuarios
        $subscriptions = PushSubscription::whereIn('user_id', $userIds)->get();

        if ($subscriptions->isEmpty()) {
            return false;
        }

        return self::dispatch($subscriptions, $title, $body, $url);
    }

    /**
     * Enviar notificación a una colección de suscripciones push.
     */
    private static function dispatch($subscriptions, string $title, string $body, string $url): bool
    {
        $publicKey = config('services.vapid.public_key');
        $privateKey = config('services.vapid.private_key');

        if (!$publicKey || !$privateKey) {
            logger()->error('[PWA Push] No se encontraron las llaves VAPID en la configuración de servicios (config/services.php).');
            return false;
        }

        $auth = [
            'VAPID' => [
                'subject' => url('/'),
                'publicKey' => $publicKey,
                'privateKey' => $privateKey,
            ],
        ];

        try {
            $webPush = new WebPush($auth);

            foreach ($subscriptions as $sub) {
                $payload = json_encode([
                    'title' => $title,
                    'body' => $body,
                    'url' => url($url)
                ]);

                $webPush->queueNotification(
                    Subscription::create([
                        'endpoint' => $sub->endpoint,
                        'publicKey' => $sub->public_key,
                        'authToken' => $sub->auth_token,
                        'contentEncoding' => $sub->content_encoding ?: 'aes128gcm',
                    ]),
                    $payload
                );
            }

            foreach ($webPush->flush() as $report) {
                $endpoint = $report->getEndpoint();
                if (!$report->isSuccess()) {
                    logger()->warning("[PWA Push] Fallo al enviar notificación a {$endpoint}: " . $report->getReason());
                    
                    // Si el proveedor push indica que el token expiró o ya no existe (404 o 410), limpiamos la suscripción
                    if (in_array($report->getStatusCode(), [404, 410])) {
                        PushSubscription::where('endpoint', $endpoint)->delete();
                        logger()->info("[PWA Push] Suscripción push expirada eliminada: {$endpoint}");
                    }
                }
            }

            return true;

        } catch (\Exception $e) {
            logger()->error('[PWA Push] Error al procesar el envío de notificaciones: ' . $e->getMessage());
            return false;
        }
    }
}
