<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class AppointmentCancelled extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $recipientType; // 'client' or 'stylist'

    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment, $recipientType = 'client')
    {
        $this->appointment = $appointment;
        $this->recipientType = $recipientType;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->recipientType === 'client'
            ? 'Cancelación de tu Cita - GIO & ANGIE'
            : 'Notificación de Cita Cancelada - GIO & ANGIE';

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $clientName = $this->appointment->client->name;
        $stylistName = $this->appointment->specialist->user->name;

        return new Content(
            view: 'emails.appointment-cancelled',
            with: [
                'recipientName' => $this->recipientType === 'client' ? $clientName : $stylistName,
                'clientName' => $clientName,
                'stylistName' => $stylistName,
                'date' => Carbon::parse($this->appointment->scheduled_date)->isoFormat('dddd, D [de] MMMM [de] YYYY'),
                'time' => Carbon::parse($this->appointment->time)->format('g:i A'),
                'services' => $this->appointment->services->pluck('name')->implode(', '),
                'totalPrice' => number_format($this->appointment->services->sum('price'), 2),
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
        return [];
    }
}
