<?php

namespace App\Models;
use App\Traits\HasDiscounts;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
     use HasDiscounts;
    protected $fillable = [
    'brand',
    'model',
    'engine',
    'price_per_day',
    'quantity',
    'category',
    'status',
    'stars',
    'image',
    ];
     public const STATUS_AVAILABLE = 'Available';
    public const STATUS_UNAVAILABLE = 'Unavailable';

    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE)->where('quantity', '>', 0);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
// Many-to-many relation to discounts via car_discount pivot table
    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'car_discount')
                    ->withTimestamps();
    }



}

