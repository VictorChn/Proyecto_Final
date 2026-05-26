<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PushSubscription;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class SendTestPush extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:test {message=Esta es una notificación de prueba de Gio Salon} {--user= : ID del usuario específico}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía una notificación push de prueba a todos los navegadores suscritos o a un usuario específico.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $messageText = $this->argument('message');
        $userId = $this->option('user');

        $query = PushSubscription::query();

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $subscriptions = $query->get();

        if ($subscriptions->isEmpty()) {
            $this->error('No se encontraron suscripciones push registradas.');
            return 1;
        }

        $this->info('Enviando notificación a ' . $subscriptions->count() . ' suscripciones...');

        $auth = [
            'VAPID' => [
                'subject' => url('/'),
                'publicKey' => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ];

        try {
            $webPush = new WebPush($auth);

            foreach ($subscriptions as $sub) {
                $payload = json_encode([
                    'title' => 'Gio Salon & Angie Nails',
                    'body' => $messageText,
                    'url' => url('/dashboard')
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

            $successCount = 0;
            $failCount = 0;

            foreach ($webPush->flush() as $report) {
                $endpoint = $report->getEndpoint();
                if ($report->isSuccess()) {
                    $this->info("[Éxito] Notificación enviada a {$endpoint}");
                    $successCount++;
                } else {
                    $this->error("[Fallo] No se pudo enviar a {$endpoint}: {$report->getReason()}");
                    
                    // Si el endpoint ya no existe en el proveedor push (expirado/desinstalado), lo limpiamos
                    if (in_array($report->getStatusCode(), [404, 410])) {
                        PushSubscription::where('endpoint', $endpoint)->delete();
                        $this->warn("Suscripción expirada eliminada: {$endpoint}");
                    }
                    $failCount++;
                }
            }

            $this->info("Proceso completado. Enviados con éxito: {$successCount}, Fallidos: {$failCount}.");
        } catch (\Exception $e) {
            $this->error("Error al inicializar WebPush: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
