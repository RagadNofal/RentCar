<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;

class carSearchController extends Controller
{
   public function search(Request $request)
{
    // Validate input
    $request->validate([
        'brand'      => ['nullable', 'string', 'max:255'],
        'model'      => ['nullable', 'string', 'max:255'],
        'min_price'  => ['nullable', 'numeric', 'min:0'],
        'max_price'  => ['nullable', 'numeric', 'gte:min_price'],
    ]);

    // Prepare the base query
    $query = Car::query();

    if ($request->filled('brand')) {
        $query->where('brand', 'like', '%' . $request->brand . '%');
    }

    if ($request->filled('model')) {
        $query->where('model', 'like', '%' . $request->model . '%');
    }

    if ($request->filled('min_price')) {
        $query->where('price_per_day', '>=', $request->min_price);
    }

    if ($request->filled('max_price')) {
        $query->where('price_per_day', '<=', $request->max_price);
    }

    $query->where('status', '=', 'available');

    $cars = $query->paginate(9);
    $cars->appends($request->except('page'));

    return view('cars.searchedCars', compact('cars'));
}

}
