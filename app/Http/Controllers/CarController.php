<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function homecars()
{
    // Only available cars, latest first, paginated
    $cars = Car::where('status', 'Available')->latest()->paginate(6);

    // Add discount and price info to each car
    $cars->each(function ($car) {
        $discount = $car->getApplicableDiscount(now());
        $car->original_price = $car->price_per_day;

        if ($discount) {
            $car->reduce = $discount->amount;
            $car->discount = $discount;
            $car->final_price = $car->price_per_day - ($car->price_per_day * $discount->amount / 100);
        } else {
            $car->reduce = 0;
            $car->discount = null;
            $car->final_price = $car->price_per_day;
        }
    });

    return view('home', compact('cars'));
}


public function index()
{
    // Only available cars, latest first, paginated
    $cars = Car::where('status', 'Available')->latest()->paginate(9);

    // Add discount and price info to each car
    $cars->each(function ($car) {
        $discount = $car->getApplicableDiscount(now());
        $car->original_price = $car->price_per_day;

        if ($discount) {
            $car->reduce = $discount->amount;
            $car->discount = $discount;
            $car->final_price = $car->price_per_day - ($car->price_per_day * $discount->amount / 100);
        } else {
            $car->reduce = 0;
            $car->discount = null;
            $car->final_price = $car->price_per_day;
        }
    });

    return view('cars.cars', compact('cars')); // or 'admin.cars.index' depending on your layout
}

    
    
   
    

}
