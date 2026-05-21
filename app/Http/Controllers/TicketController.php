<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function download(Appointment $appointment)
    {
        // Verificar que el usuario sea dueño de la cita o un administrador
        if (Auth::id() !== $appointment->client_id && !Auth::user()->hasRole('Administrador')) {
            abort(403, 'No tienes permiso para descargar este ticket.');
        }

        // Cargar las relaciones necesarias para el ticket
        $appointment->load(['services', 'specialist.user', 'client']);

        // Generar el PDF usando DomPDF
        $pdf = Pdf::loadView('pdf.ticket', compact('appointment'));

        // Configurar papel
        $pdf->setPaper('A4', 'portrait');

        // Retornar la descarga
        return $pdf->download('AuraSpa_Ticket_' . $appointment->id . '.pdf');
    }
}
