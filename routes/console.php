<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\Specialist;
use App\Models\User;
use App\Mail\StylistNextDayAppointments;
use App\Mail\AdminNextDayAppointmentsReport;
use App\Mail\AppointmentReminder;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

$notificationHour = '19';
try {
    $notificationHour = \App\Models\Setting::getVal('notification_hour', '19');
} catch (\Exception $e) {
    // Fallback if table doesn't exist yet
}

// 1. Tarea Programada: Enviar agenda diaria a los Estilistas (Diariamente a la hora configurada)
Schedule::call(function () {
    $tomorrow = Carbon::tomorrow()->format('Y-m-d');
    $dateString = Carbon::tomorrow()->isoFormat('dddd, D [de] MMMM [de] YYYY');

    // Obtener citas de mañana que NO estén canceladas ni completadas
    $appointments = Appointment::whereDate('scheduled_date', $tomorrow)
        ->whereNotIn('status', ['cancelled', 'completed', 'realizada'])
        ->with(['client', 'services', 'specialist.user'])
        ->get();

    // Agrupar por estilista
    $grouped = $appointments->groupBy('specialist_id');

    foreach ($grouped as $specialistId => $appts) {
        $specialist = Specialist::with('user')->find($specialistId);
        if ($specialist && $specialist->user && $specialist->user->email) {
            try {
                Mail::to($specialist->user->email)->send(new StylistNextDayAppointments($specialist, $appts, $dateString));
            } catch (\Exception $e) {
                logger()->error('Error al enviar agenda diaria al estilista ' . $specialist->user->email . ': ' . $e->getMessage());
            }
        }
    }
})->dailyAt("$notificationHour:00")->name('send-stylist-daily-agenda');

// 2. Tarea Programada: Enviar reporte consolidado en PDF al Administrador (Diariamente a las 7:00 PM)
Schedule::call(function () {
    $tomorrow = Carbon::tomorrow()->format('Y-m-d');
    $dateString = Carbon::tomorrow()->isoFormat('dddd, D [de] MMMM [de] YYYY');

    // Obtener citas de mañana que NO estén canceladas ni completadas
    $appointments = Appointment::whereDate('scheduled_date', $tomorrow)
        ->whereNotIn('status', ['cancelled', 'completed', 'realizada'])
        ->with(['client', 'services', 'specialist.user'])
        ->get();

    $stylistsData = [];
    $totalAppointments = $appointments->count();
    $totalRevenue = 0;

    $grouped = $appointments->groupBy('specialist_id');

    foreach ($grouped as $specialistId => $appts) {
        $specialist = Specialist::with('user')->find($specialistId);
        if ($specialist && $specialist->user) {
            $revenue = $appts->sum(fn($appt) => $appt->services->sum('price'));
            $totalRevenue += $revenue;

            $stylistsData[] = [
                'name' => $specialist->user->name,
                'specialty' => $specialist->specialty,
                'appointments' => $appts,
                'revenue' => $revenue
            ];
        }
    }

    $totalStylists = count($stylistsData);

    $kpis = [
        'totalAppointments' => $totalAppointments,
        'totalRevenue' => $totalRevenue,
        'totalStylists' => $totalStylists
    ];

    // Buscar todos los administradores
    $admins = User::role('Administrador')->get();

    foreach ($admins as $admin) {
        if ($admin->email) {
            try {
                Mail::to($admin->email)->send(new AdminNextDayAppointmentsReport($dateString, $stylistsData, $kpis));
            } catch (\Exception $e) {
                logger()->error('Error al enviar reporte diario al administrador ' . $admin->email . ': ' . $e->getMessage());
            }
        }
    }
})->dailyAt("$notificationHour:00")->name('send-admin-daily-report');

// 3. Tarea Programada: Recordatorio al Cliente 24h antes de su cita (Se ejecuta cada hora)
Schedule::call(function () {
    $tomorrow = Carbon::tomorrow()->format('Y-m-d');
    $currentHour = Carbon::now()->format('H');

    // Obtener citas de mañana en el rango de hora actual que NO estén canceladas ni completadas
    $appointments = Appointment::whereDate('scheduled_date', $tomorrow)
        ->whereTime('time', '>=', "$currentHour:00:00")
        ->whereTime('time', '<=', "$currentHour:59:59")
        ->whereNotIn('status', ['cancelled', 'completed', 'realizada'])
        ->with(['client', 'services', 'specialist.user'])
        ->get();

    foreach ($appointments as $appt) {
        if ($appt->client && $appt->client->email) {
            try {
                Mail::to($appt->client->email)->send(new AppointmentReminder($appt));
            } catch (\Exception $e) {
                logger()->error('Error al enviar recordatorio de cita al cliente ' . $appt->client->email . ': ' . $e->getMessage());
            }
        }
    }
})->hourly()->name('send-appointment-client-reminders');

// 4. Comando Artisan Auxiliar: Probar el envío de correos programados inmediatamente
Artisan::command('citas:probar-correos', function () {
    $this->info('Iniciando simulación del envío de correos programados para mañana...');
    
    $tomorrow = Carbon::tomorrow()->format('Y-m-d');
    $dateString = Carbon::tomorrow()->isoFormat('dddd, D [de] MMMM [de] YYYY');
    
    $this->info('Buscando citas para la fecha: ' . $tomorrow);

    // Obtener citas de mañana que NO estén canceladas ni completadas
    $appointments = Appointment::whereDate('scheduled_date', $tomorrow)
        ->whereNotIn('status', ['cancelled', 'completed', 'realizada'])
        ->with(['client', 'services', 'specialist.user'])
        ->get();

    $this->info('Se encontraron ' . $appointments->count() . ' citas.');

    // 1. Simular envío a estilistas
    $grouped = $appointments->groupBy('specialist_id');
    foreach ($grouped as $specialistId => $appts) {
        $specialist = Specialist::with('user')->find($specialistId);
        if ($specialist && $specialist->user && $specialist->user->email) {
            $this->line('-> Enviando agenda a estilista: ' . $specialist->user->name . ' (' . $specialist->user->email . ')...');
            try {
                Mail::to($specialist->user->email)->send(new StylistNextDayAppointments($specialist, $appts, $dateString));
                $this->info('   ¡Enviado con éxito!');
            } catch (\Exception $e) {
                $this->error('   Error: ' . $e->getMessage());
            }
        }
    }

    // 2. Simular envío a administradores
    $stylistsData = [];
    $totalAppointments = $appointments->count();
    $totalRevenue = 0;

    foreach ($grouped as $specialistId => $appts) {
        $specialist = Specialist::with('user')->find($specialistId);
        if ($specialist && $specialist->user) {
            $revenue = $appts->sum(fn($appt) => $appt->services->sum('price'));
            $totalRevenue += $revenue;

            $stylistsData[] = [
                'name' => $specialist->user->name,
                'specialty' => $specialist->specialty,
                'appointments' => $appts,
                'revenue' => $revenue
            ];
        }
    }

    $kpis = [
        'totalAppointments' => $totalAppointments,
        'totalRevenue' => $totalRevenue,
        'totalStylists' => count($stylistsData)
    ];

    $admins = User::role('Administrador')->get();
    foreach ($admins as $admin) {
        if ($admin->email) {
            $this->line('-> Enviando reporte consolidado con PDF a Administrador: ' . $admin->name . ' (' . $admin->email . ')...');
            try {
                Mail::to($admin->email)->send(new AdminNextDayAppointmentsReport($dateString, $stylistsData, $kpis));
                $this->info('   ¡Enviado con éxito!');
            } catch (\Exception $e) {
                $this->error('   Error: ' . $e->getMessage());
            }
        }
    }

    // 3. Simular envío de recordatorios de citas a los clientes
    $this->info('-> Iniciando simulación de recordatorios a clientes para mañana...');
    foreach ($appointments as $appt) {
        if ($appt->client && $appt->client->email) {
            $this->line('   -> Enviando recordatorio a cliente: ' . $appt->client->name . ' (' . $appt->client->email . ') para su cita a las ' . Carbon::parse($appt->time)->format('g:i A') . '...');
            try {
                Mail::to($appt->client->email)->send(new AppointmentReminder($appt));
                $this->info('      ¡Enviado con éxito!');
            } catch (\Exception $e) {
                $this->error('      Error: ' . $e->getMessage());
            }
        }
    }

    $this->info('Simulación completada con éxito.');
})->purpose('Simula y fuerza el envío inmediato de los correos programados para mañana.');


// 5. Tarea Programada: Cancelar automáticamente citas pendientes de pago tras 15 minutos (Se ejecuta cada 5 minutos)
Schedule::call(function () {
    $unpaidAppointments = Appointment::where('status', 'pending')
        ->where('payment_status', 'pending')
        ->where('created_at', '<=', now()->subMinutes(15))
        ->get();

    foreach ($unpaidAppointments as $appt) {
        // Cancelar de forma segura (para liberar el horario y limpiar el panel)
        $appt->update([
            'status' => 'cancelled',
        ]);
        logger()->info("Cita ID {$appt->id} cancelada automáticamente por falta de pago de anticipo (límite de 15 minutos excedido).");
    }
})->everyFiveMinutes()->name('cancel-unpaid-appointments');


