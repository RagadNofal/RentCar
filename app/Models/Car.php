<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'brand',
        'model',
        'engine',
        'price_per_day',
        'image',
        'quantity',
        'reduce',
        'stars',
    ];
     public const STATUS_AVAILABLE = 'Available';
    public const STATUS_UNAVAILABLE = 'Unavailable';

    // Optional scope
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE)->where('quantity', '>', 0);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}

