<?php

namespace App\Mail;

use App\Models\Specialist;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Mail\Mailables\Attachment;

class StylistNextDayAppointments extends Mailable
{
    use Queueable, SerializesModels;

    public $specialist;
    public $appointments;
    public $dateString;

    /**
     * Create a new message instance.
     */
    public function __construct(Specialist $specialist, Collection $appointments, string $dateString)
    {
        $this->specialist = $specialist;
        $this->appointments = $appointments;
        $this->dateString = $dateString;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu Agenda Diaria de Citas - GIO & ANGIE / AuraSpa',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.stylist-next-day-appointments',
            with: [
                'stylistName' => $this->specialist->user->name,
                'date' => $this->dateString,
                'appointments' => $this->appointments,
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
        $pdf = Pdf::loadView('pdf.stylist-appointments-report', [
            'date' => $this->dateString,
            'specialist' => $this->specialist,
            'appointments' => $this->appointments,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $tomorrowDate = date('Y-m-d', strtotime('+1 day'));
        $fileName = 'Mi_Agenda_' . $tomorrowDate . '.pdf';

        return [
            Attachment::fromData(fn () => $pdf->output(), $fileName)
                ->withMime('application/pdf'),
        ];
    }
}
