<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'reservation_id',
        'payment_method',
         'discount_id',
        'payment_status',
        'amount',
    ];

    // Enums
   

    public const STATUS_PENDING = 'Pending';
    public const STATUS_PAID = 'Paid';
    public const STATUS_CANCELED = 'Canceled';

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
