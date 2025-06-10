<?php

namespace App\Notifications;


use Illuminate\Notifications\Notification;

use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Car;

class NewCarNotification extends Notification 
{
    

    public $car;

    public function __construct( $car)
    {
        $this->car = $car;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
{
    return [
        'type' => 'New Car',
        'message' => "🚘 New car added: {$this->car->brand} {$this->car->model} now available!",
        'car_id' => $this->car->id,
        'price_per_day' => $this->car->price_per_day,
        'brand' => $this->car->brand,
        'model' => $this->car->model,
    ];
}

}
