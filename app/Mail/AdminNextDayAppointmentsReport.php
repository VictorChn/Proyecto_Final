<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Mail\Mailables\Attachment;

class AdminNextDayAppointmentsReport extends Mailable
{
    use Queueable, SerializesModels;

    public $dateString;
    public $stylistsData;
    public $kpis;

    /**
     * Create a new message instance.
     */
    public function __construct(string $dateString, array $stylistsData, array $kpis)
    {
        $this->dateString = $dateString;
        $this->stylistsData = $stylistsData;
        $this->kpis = $kpis;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Consolidado Diario de Citas - GIO & ANGIE / AuraSpa',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-next-day-appointments-report',
            with: [
                'date' => $this->dateString,
                'totalAppointments' => $this->kpis['totalAppointments'],
                'totalRevenue' => $this->kpis['totalRevenue'],
                'totalStylists' => $this->kpis['totalStylists'],
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('pdf.admin-appointments-report', [
            'date' => $this->dateString,
            'stylistsData' => $this->stylistsData,
            'totalAppointments' => $this->kpis['totalAppointments'],
            'totalRevenue' => $this->kpis['totalRevenue'],
            'totalStylists' => $this->kpis['totalStylists'],
        ]);

        $pdf->setPaper('A4', 'portrait');

        // Generar un nombre de archivo bonito con la fecha de mañana
        $tomorrowDate = date('Y-m-d', strtotime('+1 day'));
        $fileName = 'Consolidado_Citas_' . $tomorrowDate . '.pdf';

        return [
            Attachment::fromData(fn () => $pdf->output(), $fileName)
                ->withMime('application/pdf'),
        ];
    }
}
