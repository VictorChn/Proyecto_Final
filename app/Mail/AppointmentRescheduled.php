<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class AppointmentRescheduled extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu Cita ha sido Reagendada - GIO & ANGIE',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-rescheduled',
            with: [
                'clientName' => $this->appointment->client->name,
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
