<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class AppointmentNotification extends Notification
{
    public $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('You have a new appointment.')
            ->line('Doctor: ' . $this->appointment->doctor->name)
            ->line('Date: ' . $this->appointment->appointment_date)
            ->action('View Appointment', url('/appointments/' . $this->appointment->id))
            ->line('Thank you for using our application!');
    }
}