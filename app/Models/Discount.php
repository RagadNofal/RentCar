<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
     protected $fillable = [
        'type',
        'code',
        'category',
        'amount',
        'start_date',
        'end_date',
        'is_active',
        'description',
    ];
  // Many-to-many relation to cars via car_discount pivot table
    public function cars()
    {
        return $this->belongsToMany(Car::class, 'car_discount')
                    ->withTimestamps();
    }


}
