<?php

namespace App\Notifications;


use Illuminate\Notifications\Notification;

use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Discount;

class NewDiscountNotification extends Notification
{


    public $discount;

    public function __construct(Discount $discount)
    {
        $this->discount = $discount;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

   public function toDatabase($notifiable)
{
    return [
        'type' => 'New Discount',
        'message' => $this->discount->description 
            ?? ($this->discount->code 
                ? "🎉 New discount available! Use code '{$this->discount->code}' to save!" 
                : "🚗 A new discount is now active for cars!"),
        'code' => $this->discount->code,
        'amount' => $this->discount->amount,
        'valid_until' => $this->discount->end_date,
    ];
}

}
