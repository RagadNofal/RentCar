<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Reservation;

class ReservationMadeNotification extends Notification
{
    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'Reservation',
            'reservation_id' => $this->reservation->id,
            'start_date' => $this->reservation->start_date,
            'end_date' => $this->reservation->end_date,
            'days' => $this->reservation->days,
            'price_per_day' => $this->reservation->price_per_day,
            'pickup_location' => $this->reservation->pickup_location,
            'dropoff_location' => $this->reservation->dropoff_location,
            'status' => $this->reservation->status,
            'message' => "✅ Your reservation from {$this->reservation->start_date} to {$this->reservation->end_date} has been confirmed.",
        ];
    }
}
