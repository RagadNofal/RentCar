<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'car_id',
        'start_date',
        'end_date',
        'days',
        'price_per_day',
        'total_price',
        'pickup_location',
        'dropoff_location',
        'status',
    ];

    // Enums: Status
    public const STATUS_ACTIVE = 'Active';
    public const STATUS_PENDING = 'Pending';
    public const STATUS_CANCELED = 'Canceled';
    
    public const STATUS_COMPLETED = 'Completed';

    // Enums: Locations
    public const LOCATIONS = [
        'Company Site',
        'Queen Alia Airport',
        '7th Circle',
        'Mecca Street',
        'University of Jordan',
        'Downtown',
        'Other',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
