<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\User;
use App\Notifications\NewCarNotification;
use Illuminate\Http\Request;
use luminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class CarController extends Controller
{
public function index()
{
    $cars = Car::latest()->paginate(8);

    foreach ($cars as $car) {
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
    }

    return view('admin.cars.index', compact('cars'));
}


    public function create()
    {
        return view('admin.cars.create');
    }

    public function store(Request $request)
    {
       
        $validatedData = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'engine' => 'required|string|max:255',
            'price_per_day' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'category' => 'nullable|string|max:255',
            'status' => 'required|in:Available,Unavailable',
            
            'stars' => 'required|integer|min:0|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imageName = $request->brand . '-' . $request->model . '-' . $request->engine . '-' . Str::random(10) . '.' . $request->file('image')->extension();
            $image = $request->file('image');
            $path = $image->storeAs('images/cars', $imageName);
            $validatedData['image'] = '/' . $path;
        }

        $car = Car::create($validatedData);
        

        $users = User::where('role', 'client')->get();

        foreach ($users as $user) {
            $user->notify(new NewCarNotification($car));
        }

//Log::info('Notifications sent to users');

        return redirect()->route('admin.cars.index')->with('success', 'Car added successfully');
    }

public function show(Car $car)
{
    $discount = $car->getApplicableDiscount(now());

    $car->original_price = $car->price_per_day;

    if ($discount) {
        $car->reduce = $discount->amount;
        $car->final_price = $car->price_per_day - ($car->price_per_day * $discount->amount / 100);
    } else {
        $car->reduce = 0;
        $car->final_price = $car->price_per_day;
    }

    return view('admin.cars.show', compact('car', 'discount'));
}

    public function edit(Car $car)
    {
        return view('admin.cars.edit', compact('car'));
    }

    public function update(Request $request, Car $car)
    {
        $validatedData = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'engine' => 'required|string|max:255',
            'price_per_day' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'category' => 'nullable|string|max:255',
            'status' => 'required|in:Available,Unavailable',
            
            'stars' => 'required|integer|min:0|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

         if ($request->hasFile('image')) {
        // Delete the old image if it exists
        if ($car->image && Storage::exists(ltrim($car->image, '/'))) {
            Storage::delete(ltrim($car->image, '/'));
        }

        $imageName = $request->brand . '-' . $request->model . '-' . $request->engine . '-' . Str::random(10) . '.' . $request->file('image')->extension();
        $image = $request->file('image');
        $path = $image->storeAs('images/cars', $imageName);
        $validatedData['image'] = '/' . $path;
    }

    $car->update($validatedData);

       

        return redirect()->route('admin.cars.index')->with('success', 'Car updated successfully');
    }

    public function destroy(Car $car)
    {
        if ($car->image && Storage::disk('public')->exists(str_replace('storage/', '', $car->image))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $car->image));
        }

        $car->delete();

        return redirect()->route('admin.cars.index')->with('success', 'Car deleted successfully');
    }

    public function updateStatus(Request $request, Car $car)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:Available,Unavailable',
        ]);

        $car->update(['status' => $validatedData['status']]);

        return back()->with('success', 'Car status updated successfully');
    }
public function rentalHistory(Car $car) 
{
    $rentals = $car->reservations()->with(['user', 'payment'])->latest()->get();
    return view('admin.cars.rental-history', compact('car', 'rentals'));
}






}
